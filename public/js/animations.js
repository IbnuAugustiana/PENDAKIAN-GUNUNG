/* =====================================================================
   SIMAKSI.COM — animations.js
   ---------------------------------------------------------------------
   Seluruh animasi & micro-interaction UI dikumpulkan di sini.
   Library: GSAP 3.13 + ScrollTrigger (CDN, dimuat sebelum file ini).

   Prinsip:
   - Hanya transform & opacity yang dianimasikan (60fps).
   - Animasi scroll berjalan sekali (kecuali parallax).
   - prefers-reduced-motion dihormati: animasi gerak dimatikan.
   - Mobile: tilt 3D, cursor custom, dan parallax berlapis dinonaktifkan.
   - Tidak mengganggu fungsi: tidak ada preventDefault pada form/tombol,
     konten tetap tampil bila GSAP gagal dimuat (class .anim dicabut).

   Daftar isi
   00. Setup & util
   01. Preloader
   02. Navbar (intro, scroll state, indikator aktif, drawer mobile)
   03. Scroll progress & tombol kembali ke atas
   04. Hero (split text, stagger, parallax, tilt, floating)
   05. Scroll reveal (variasi + stagger grid)
   06. Counter angka
   07. Alur step (garis tergambar + step menyala)
   08. Parallax umum & tilt kartu
   09. Skeleton gambar
   10. Micro-interaction: ripple
   11. Form: shake error, checkmark valid, loading submit
   12. Toast notifikasi
   13. Badge status (pulse) & tabel
   14. Cursor custom
   15. Admin shell
   16. Init
   ===================================================================== */
(function () {
    'use strict';

    /* -----------------------------------------------------------------
       00. SETUP & UTIL
       ----------------------------------------------------------------- */
    var root = document.documentElement;
    var body = document.body;
    var mq = function (q) { return !!(window.matchMedia && window.matchMedia(q).matches); };

    var REDUCED = mq('(prefers-reduced-motion: reduce)');
    var FINE_POINTER = mq('(hover: hover) and (pointer: fine)');
    var DESKTOP = mq('(min-width: 992px)');
    var gsap = window.gsap;
    var ScrollTrigger = window.ScrollTrigger;
    // .anim bisa sudah dicabut oleh pengaman 4 detik (partials/ui/head) bila
    // script lambat termuat — saat itu konten sudah tampil, jangan disembunyikan lagi.
    var HAS_GSAP = !!(gsap && ScrollTrigger) && !REDUCED && root.classList.contains('anim');
    var EASE = 'power3.out';

    if (HAS_GSAP) {
        gsap.registerPlugin(ScrollTrigger);
        gsap.defaults({ ease: EASE, duration: 0.8 });
    } else {
        // GSAP gagal dimuat / reduced motion → tampilkan semua konten apa adanya.
        root.classList.remove('anim');
    }
    window.SIMAKSI_ANIM_READY = true;

    var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
    var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

    /** Callback scroll terpusat, dijalankan sekali per frame. */
    var scrollHandlers = [];
    var ticking = false;
    function onScroll(fn) { scrollHandlers.push(fn); }
    function runScroll() {
        var y = window.scrollY || window.pageYOffset;
        for (var i = 0; i < scrollHandlers.length; i++) scrollHandlers[i](y);
        ticking = false;
    }
    window.addEventListener('scroll', function () {
        if (!ticking) { window.requestAnimationFrame(runScroll); ticking = true; }
    }, { passive: true });

    /* -----------------------------------------------------------------
       01. PRELOADER — siluet gunung tergambar, maks ±1 detik,
           hanya pada kunjungan pertama per sesi (lihat partials/ui/head).
       ----------------------------------------------------------------- */
    function initPreloader() {
        var pl = $('.preloader');
        if (!pl) return 0;
        if (root.classList.contains('intro-seen') || REDUCED) { pl.remove(); return 0; }
        setTimeout(function () {
            pl.classList.add('is-done');
            setTimeout(function () { pl.remove(); }, 650);
        }, 850);
        return 0.8; // jeda (detik) sebelum intro halaman dimulai
    }

    /* -----------------------------------------------------------------
       02. NAVBAR
       ----------------------------------------------------------------- */
    function initNavbar(delay) {
        var nav = $('.site-nav');
        if (!nav) return;

        // Intro: turun dari atas + fade. Pada navigasi berikutnya yang
        // memakai View Transitions, navbar cukup langsung tampil (tidak berulang).
        var firstOfSession = !root.classList.contains('intro-seen');
        if (HAS_GSAP && (firstOfSession || root.classList.contains('no-vt'))) {
            gsap.fromTo(nav, { yPercent: -100, autoAlpha: 0 }, {
                yPercent: 0, autoAlpha: 1, duration: 0.9, delay: delay,
                clearProps: 'transform,opacity,visibility'
            });
        }
        root.classList.add('anim-ready');

        // Berubah ringkas + glass saat scroll
        onScroll(function (y) { nav.classList.toggle('is-scrolled', y > 24); });

        // Indikator menu aktif yang meluncur (transform-only)
        var list = $('.nav-links', nav);
        if (list) {
            var ind = document.createElement('span');
            ind.className = 'nav-indicator';
            ind.setAttribute('aria-hidden', 'true');
            list.appendChild(ind);
            list.classList.add('has-indicator');
            var active = $('a.active', list);

            var moveTo = function (a, instant) {
                if (!a) { ind.style.opacity = '0'; return; }
                var lr = list.getBoundingClientRect();
                var r = a.getBoundingClientRect();
                if (instant) ind.style.transition = 'none';
                ind.style.transform = 'translateX(' + (r.left - lr.left) + 'px) scaleX(' + (r.width / 100) + ')';
                ind.style.opacity = '1';
                if (instant) { void ind.offsetWidth; ind.style.transition = ''; }
            };
            var settle = function () { moveTo(active, true); };
            // Tunggu font termuat agar lebar link akurat
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(settle); else settle();
            window.addEventListener('resize', settle);
            $$('a', list).forEach(function (a) {
                a.addEventListener('mouseenter', function () { moveTo(a); });
                a.addEventListener('focus', function () { moveTo(a); });
            });
            list.addEventListener('mouseleave', function () { moveTo(active); });
        }

        // Drawer mobile (hamburger)
        var burger = $('[data-nav-toggle]');
        var drawer = $('#navDrawer');
        var overlay = $('[data-nav-overlay]');
        if (burger && drawer) {
            var setOpen = function (open) {
                body.classList.toggle('nav-open', open);
                burger.setAttribute('aria-expanded', open ? 'true' : 'false');
                burger.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
                if (open) {
                    var first = $('a, button, input', drawer);
                    if (first) setTimeout(function () { first.focus({ preventScroll: true }); }, 300);
                }
            };
            burger.addEventListener('click', function () { setOpen(!body.classList.contains('nav-open')); });
            if (overlay) overlay.addEventListener('click', function () { setOpen(false); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && body.classList.contains('nav-open')) { setOpen(false); burger.focus(); }
            });
            window.addEventListener('resize', function () { if (window.innerWidth >= 992) setOpen(false); });
        }
    }

    /* -----------------------------------------------------------------
       03. SCROLL PROGRESS & KEMBALI KE ATAS
       ----------------------------------------------------------------- */
    function initScrollUI() {
        var bar = $('.scroll-progress');
        var toTop = $('.to-top');
        var ring = toTop ? $('circle', toTop) : null;

        onScroll(function (y) {
            var max = document.documentElement.scrollHeight - window.innerHeight;
            var p = max > 0 ? Math.min(1, y / max) : 0;
            if (bar) bar.style.transform = 'scaleX(' + p + ')';
            if (toTop) {
                toTop.classList.toggle('is-visible', y > 600);
                if (ring) ring.style.strokeDashoffset = String(157 * (1 - p));
            }
        });

        if (toTop) {
            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: REDUCED ? 'auto' : 'smooth' });
            });
        }
        runScroll();
    }

    /* -----------------------------------------------------------------
       04. HERO
       ----------------------------------------------------------------- */

    /** Memecah teks menjadi span per kata (aman untuk elemen bersarang). */
    function splitWords(el) {
        if (el.dataset.splitDone) return;
        el.dataset.splitDone = '1';
        var walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null);
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        nodes.forEach(function (node) {
            if (!node.textContent.trim()) return;
            if (node.parentNode.closest && node.parentNode.closest('svg')) return;
            var frag = document.createDocumentFragment();
            node.textContent.split(/(\s+)/).forEach(function (part) {
                if (!part) return;
                if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
                var w = document.createElement('span');
                w.className = 'word';
                var inner = document.createElement('span');
                inner.className = 'word-inner';
                inner.textContent = part;
                w.appendChild(inner);
                frag.appendChild(w);
            });
            node.parentNode.replaceChild(frag, node);
        });
        // Gradient bergerak dipindah ke tiap kata agar tetap tampil saat kata dianimasikan
        $$('.text-gradient', el).forEach(function (g) {
            $$('.word-inner', g).forEach(function (w) { w.classList.add('text-gradient'); });
            g.classList.remove('text-gradient');
        });
    }

    function initHero(delay) {
        var hero = $('.hero');
        if (!hero || !HAS_GSAP) return;

        var title = $('[data-split]', hero);
        if (title) splitWords(title);

        var tl = gsap.timeline({ delay: delay + 0.05, defaults: { ease: EASE } });
        tl.fromTo('.hero__bg', { scale: 1.15, autoAlpha: 0 }, { scale: 1, autoAlpha: 1, duration: 1.8, ease: 'power2.out' }, 0)
          .from('.hero__layers svg', { yPercent: 40, duration: 1.4, stagger: 0.12 }, 0.1)
          .fromTo('.hero__badge', { y: 20, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7 }, 0.25);

        if (title) {
            tl.set(title, { autoAlpha: 1 }, 0.35)
              .fromTo($$('.word-inner', title), { yPercent: 115, rotation: 4 },
                  { yPercent: 0, rotation: 0, duration: 1, stagger: 0.06 }, 0.35)
              .to('.hero__underline path', { strokeDashoffset: 0, duration: 1.1, ease: 'power2.inOut' }, '-=0.35');
        }

        tl.fromTo($$('.hero__sub, .hero__actions > *, .hero__trust > *', hero),
                { y: 24, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.7, stagger: 0.08, clearProps: 'transform' }, 0.85)
          .fromTo('.hero__visual', { y: 60, rotationY: -14, autoAlpha: 0 },
                { y: 0, rotationY: 0, autoAlpha: 1, duration: 1.3, clearProps: 'transform' }, 0.55)
          .fromTo('.float-badge__box', { scale: 0.6, y: 16, autoAlpha: 0 },
                { scale: 1, y: 0, autoAlpha: 1, duration: 0.7, stagger: 0.14, ease: 'back.out(1.7)' }, 1.15)
          .fromTo('.scroll-cue', { autoAlpha: 0, y: -10 }, { autoAlpha: 1, y: 0, duration: 0.6 }, 1.5);

        // Parallax: latar bergerak lebih lambat dari konten
        var st = { trigger: hero, start: 'top top', end: 'bottom top', scrub: true };
        gsap.to('.hero__bg', { yPercent: 16, ease: 'none', scrollTrigger: st });
        if (DESKTOP) {
            // Siluet berlapis dengan kecepatan berbeda (desktop saja)
            gsap.to('.hero__layer-far', { y: 110, ease: 'none', scrollTrigger: st });
            gsap.to('.hero__layer-mid', { y: 55, ease: 'none', scrollTrigger: st });
            gsap.to('.hero__container', { y: -70, autoAlpha: 0.35, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom 20%', scrub: true } });
        }
    }

    /* -----------------------------------------------------------------
       05. SCROLL REVEAL — variasi animasi, berjalan sekali
       ----------------------------------------------------------------- */
    var VARIANTS = {
        'fade-up':    { y: 48 },
        'fade-down':  { y: -36 },
        'fade-left':  { x: -64 },   // masuk dari kiri
        'fade-right': { x: 64 },    // masuk dari kanan
        'scale':      { scale: 0.9 },
        'zoom-out':   { scale: 1.08 },
        'tilt':       { y: 40, rotation: 2.5 }
    };

    /** Elemen sudah terlihat saat halaman dimuat? → animasikan langsung (tanpa menunggu scroll). */
    function inViewNow(el) {
        var r = el.getBoundingClientRect();
        return r.top < window.innerHeight * 0.95 && r.bottom > 0;
    }

    function initReveal() {
        if (!HAS_GSAP) return;

        $$('[data-reveal]').forEach(function (el) {
            var from = Object.assign({ autoAlpha: 0 }, VARIANTS[el.dataset.reveal] || VARIANTS['fade-up']);
            var to = {
                autoAlpha: 1, x: 0, y: 0, scale: 1, rotation: 0,
                duration: parseFloat(el.dataset.duration) || 0.9,
                delay: parseFloat(el.dataset.delay) || 0,
                clearProps: 'transform'
            };
            if (!inViewNow(el)) to.scrollTrigger = { trigger: el, start: 'top 88%', once: true };
            gsap.fromTo(el, from, to);
        });

        // Grid kartu: muncul berurutan saat masuk viewport
        $$('[data-stagger]').forEach(function (group) {
            var items = Array.prototype.slice.call(group.children);
            if (!items.length) return;
            var from = Object.assign({ autoAlpha: 0 }, VARIANTS[group.dataset.stagger] || { y: 44, scale: 0.97 });
            var play = function (batch) {
                gsap.fromTo(batch, from, {
                    autoAlpha: 1, x: 0, y: 0, scale: 1, rotation: 0,
                    duration: 0.85, stagger: 0.1, clearProps: 'transform'
                });
            };
            var now = items.filter(inViewNow);
            var later = items.filter(function (i) { return now.indexOf(i) === -1; });
            if (now.length) play(now);
            if (later.length) ScrollTrigger.batch(later, { start: 'top 92%', once: true, onEnter: play });
        });

        // Pengaman: elemen di dasar halaman yang tidak pernah melewati garis
        // pemicu (halaman pendek / sudah mentok bawah) tetap dimunculkan.
        var revealStragglers = function () {
            var max = ScrollTrigger.maxScroll(window);
            if ((window.scrollY || 0) < max - 4) return;
            $$('[data-reveal], [data-stagger] > *').forEach(function (el) {
                if (parseFloat(getComputedStyle(el).opacity) > 0.01) return;
                var r = el.getBoundingClientRect();
                if (r.top > window.innerHeight || r.bottom < 0) return;
                gsap.to(el, { autoAlpha: 1, x: 0, y: 0, scale: 1, rotation: 0, duration: 0.8, overwrite: 'auto', clearProps: 'transform' });
            });
        };
        setTimeout(function () { revealStragglers(); onScroll(revealStragglers); }, 1500);

        // Counter yang tak pernah terpicu (halaman pendek): tampilkan nilai akhirnya
        setTimeout(function () {
            if ((window.scrollY || 0) < ScrollTrigger.maxScroll(window) - 4) return;
            $$('[data-count]').forEach(function (el) {
                if (el.textContent.trim() === '0' && el.dataset.count !== '0') {
                    var obj = { v: 0 }, end = parseFloat(el.dataset.count);
                    gsap.to(obj, { v: end, duration: 1, onUpdate: function () { el.textContent = (el.dataset.prefix || '') + Math.round(obj.v).toLocaleString('id-ID') + (el.dataset.suffix || ''); } });
                }
            });
        }, 1600);
    }

    /* -----------------------------------------------------------------
       06. COUNTER — angka naik dari 0 saat terlihat
       ----------------------------------------------------------------- */
    function initCounters() {
        if (!HAS_GSAP) return; // tanpa GSAP: angka asli dari server tetap tampil
        $$('[data-count]').forEach(function (el) {
            var raw = String(el.dataset.count || '0');
            var end = parseFloat(raw.replace(',', '.'));
            if (!isFinite(end)) return;
            var dec = (raw.split('.')[1] || '').length;
            var prefix = el.dataset.prefix || '';
            var suffix = el.dataset.suffix || '';
            var fmt = function (v) {
                return prefix + v.toLocaleString('id-ID', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + suffix;
            };
            var obj = { v: 0 };
            el.textContent = fmt(0);
            var tw = {
                v: end, duration: Math.min(2.2, 1 + end / 200), ease: 'power2.out', delay: 0.2,
                onUpdate: function () { el.textContent = fmt(dec ? obj.v : Math.round(obj.v)); },
                onComplete: function () { el.textContent = fmt(end); }
            };
            if (!inViewNow(el)) tw.scrollTrigger = { trigger: el, start: 'top 92%', once: true };
            gsap.to(obj, tw);
        });
    }

    /* -----------------------------------------------------------------
       07. ALUR STEP — garis penghubung tergambar mengikuti scroll,
           tiap step menyala bergantian (tidak padam lagi saat scroll naik)
       ----------------------------------------------------------------- */
    function initSteps() {
        $$('[data-steps]').forEach(function (wrap) {
            var fill = $('.steps__fill', wrap);
            var items = $$('.step', wrap);
            if (!HAS_GSAP || !fill) { items.forEach(function (s) { s.classList.add('is-active'); }); return; }

            var vertical = !mq('(min-width: 768px)');
            var prop = vertical ? 'scaleY' : 'scaleX';
            var maxP = 0;
            var setFill = gsap.quickTo(fill, prop, { duration: 0.5, ease: 'power2.out' });

            ScrollTrigger.create({
                trigger: wrap, start: 'top 78%', end: 'bottom 55%',
                onUpdate: function (self) {
                    if (self.progress <= maxP) return;
                    maxP = self.progress;
                    setFill(maxP);
                    var n = items.length;
                    items.forEach(function (it, i) {
                        if (maxP >= (n > 1 ? i / (n - 1) : 0) * 0.96) it.classList.add('is-active');
                    });
                }
            });
        });
    }

    /* -----------------------------------------------------------------
       08. PARALLAX UMUM & TILT 3D
       ----------------------------------------------------------------- */
    function initParallax() {
        if (!HAS_GSAP) return;
        $$('[data-parallax]').forEach(function (el) {
            if (!DESKTOP && !el.hasAttribute('data-parallax-mobile')) return;
            var amount = parseFloat(el.dataset.parallax) || 12;
            var trigger = el.closest('section, .page-header, .cta, .hero') || el;
            gsap.fromTo(el, { yPercent: -amount / 2 }, {
                yPercent: amount / 2, ease: 'none',
                scrollTrigger: { trigger: trigger, start: 'top bottom', end: 'bottom top', scrub: true }
            });
        });
    }

    /** Tilt 3D mengikuti kursor — desktop dengan pointer presisi saja. */
    function initTilt() {
        if (!HAS_GSAP || !FINE_POINTER || !DESKTOP) return;
        $$('[data-tilt]').forEach(function (el) {
            var max = parseFloat(el.dataset.tilt) || 8;
            var lift = el.hasAttribute('data-tilt-lift') ? -8 : 0;
            el.classList.add('has-tilt'); // matikan transisi transform CSS agar tidak bentrok dgn GSAP
            gsap.set(el, { transformPerspective: 900 });
            var rx = gsap.quickTo(el, 'rotationX', { duration: 0.6, ease: 'power3' });
            var ry = gsap.quickTo(el, 'rotationY', { duration: 0.6, ease: 'power3' });

            el.addEventListener('pointerenter', function () {
                el.classList.add('is-tilting');
                if (lift) gsap.to(el, { y: lift, duration: 0.45 });
            });
            el.addEventListener('pointermove', function (e) {
                var r = el.getBoundingClientRect();
                var px = (e.clientX - r.left) / r.width - 0.5;
                var py = (e.clientY - r.top) / r.height - 0.5;
                ry(px * max * 2);
                rx(-py * max * 2);
                el.style.setProperty('--gx', ((px + 0.5) * 100).toFixed(1) + '%');
                el.style.setProperty('--gy', ((py + 0.5) * 100).toFixed(1) + '%');
            });
            el.addEventListener('pointerleave', function () {
                el.classList.remove('is-tilting');
                gsap.to(el, { rotationX: 0, rotationY: 0, y: 0, duration: 0.7, ease: 'power3.out' });
            });
        });
    }

    /* -----------------------------------------------------------------
       09. SKELETON — shimmer sampai gambar termuat
       ----------------------------------------------------------------- */
    function initSkeletons() {
        $$('.skeleton').forEach(function (wrap) {
            var img = $('img', wrap);
            var done = function () { wrap.classList.add('is-loaded'); };
            if (!img || img.complete) { done(); return; }
            img.addEventListener('load', done, { once: true });
            img.addEventListener('error', done, { once: true });
        });
    }

    /* -----------------------------------------------------------------
       10. RIPPLE — efek gelombang saat tombol diklik
       ----------------------------------------------------------------- */
    function initRipple() {
        if (REDUCED) return;
        document.addEventListener('pointerdown', function (e) {
            var btn = e.target.closest('.btn, .btn-google, .quick-action, .btn-search');
            if (!btn || btn.disabled) return;
            var r = btn.getBoundingClientRect();
            var size = Math.max(r.width, r.height) * 2.2;
            var dot = document.createElement('span');
            dot.className = 'ripple';
            dot.setAttribute('aria-hidden', 'true');
            dot.style.width = dot.style.height = size + 'px';
            dot.style.left = (e.clientX - r.left - size / 2) + 'px';
            dot.style.top = (e.clientY - r.top - size / 2) + 'px';
            btn.appendChild(dot);
            setTimeout(function () { dot.remove(); }, 700);
        });
    }

    /* -----------------------------------------------------------------
       11. FORM — shake saat error, checkmark saat valid, loading submit
       ----------------------------------------------------------------- */
    function shake(el) {
        if (REDUCED || !el) return;
        el.classList.remove('shake');
        void el.offsetWidth; // restart animasi
        el.classList.add('shake');
        setTimeout(function () { el.classList.remove('shake'); }, 550);
    }

    function initForms() {
        var isFieldInvalid = function (el) { return el.classList.contains('is-invalid') || el.classList.contains('error'); };

        // a) Shake saat field berubah menjadi invalid (validasi JS yang sudah ada)
        if ('MutationObserver' in window) {
            var mo = new MutationObserver(function (records) {
                records.forEach(function (rec) {
                    var el = rec.target;
                    var was = (rec.oldValue || '').split(/\s+/);
                    var wasInvalid = was.indexOf('is-invalid') > -1 || was.indexOf('error') > -1;
                    if (!wasInvalid && isFieldInvalid(el) && /^(INPUT|SELECT|TEXTAREA)$/.test(el.tagName)) {
                        shake(el.closest('.field-float') || el);
                        var ff = el.closest('.field-float');
                        if (ff) ff.classList.remove('is-valid');
                    }
                });
            });
            mo.observe(document.body, { subtree: true, attributes: true, attributeFilter: ['class'], attributeOldValue: true });
        }
        // Error dari server (setelah submit gagal): shake sekali saat halaman dimuat
        setTimeout(function () {
            $$('.is-invalid, .f-input.error').forEach(function (el) { shake(el.closest('.field-float') || el); });
        }, 900);

        // b) Checkmark beranimasi untuk field floating-label yang valid
        var checkValid = function (input) {
            var ff = input.closest('.field-float');
            if (!ff) return;
            var ok = input.value.trim() !== '' && input.checkValidity() && !isFieldInvalid(input);
            ff.classList.toggle('is-valid', ok);
        };
        document.addEventListener('input', function (e) {
            if (e.target.classList && e.target.classList.contains('f-input')) {
                var t = e.target;
                setTimeout(function () { checkValid(t); }, 0); // setelah validasi bawaan halaman berjalan
            }
        });
        document.addEventListener('focusout', function (e) {
            if (e.target.classList && e.target.classList.contains('f-input')) checkValid(e.target);
        });
        $$('.f-input').forEach(function (i) { if (i.value) checkValid(i); });

        // c) Loading spinner pada tombol submit.
        //    Dipasang di fase bubble & hanya jika submit TIDAK dibatalkan oleh
        //    validasi/konfirmasi yang sudah ada — jadi tidak mengubah alur form.
        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (e.defaultPrevented) {
                $$('.is-invalid, .f-input.error', form).forEach(function (el) { shake(el.closest('.field-float') || el); });
                return;
            }
            if (form.hasAttribute('data-no-loading') || form.target === '_blank') return;
            var btn = e.submitter || $('button[type="submit"], button:not([type])', form);
            if (!btn || btn.tagName !== 'BUTTON' || btn.classList.contains('is-loading')) return;
            btn.classList.add('is-loading');
            btn.setAttribute('aria-busy', 'true');
            // Pengaman: kembalikan tombol bila halaman tidak berpindah (mis. unduhan)
            setTimeout(function () { btn.classList.remove('is-loading'); btn.removeAttribute('aria-busy'); }, 8000);
        });
        // Kembali via tombol back (bfcache): bersihkan state loading
        window.addEventListener('pageshow', function () {
            $$('.is-loading').forEach(function (b) { b.classList.remove('is-loading'); b.removeAttribute('aria-busy'); });
        });

        // d) Bootstrap: refresh posisi ScrollTrigger saat accordion/collapse berubah tinggi
        if (HAS_GSAP) {
            document.addEventListener('shown.bs.collapse', function () { ScrollTrigger.refresh(); });
            document.addEventListener('hidden.bs.collapse', function () { ScrollTrigger.refresh(); });
        }
    }

    /* -----------------------------------------------------------------
       12. TOAST — pesan flash sukses/error masuk dengan slide,
           lalu hilang otomatis (menggantikan auto-hide lama di layout)
       ----------------------------------------------------------------- */
    var CHECK_SVG = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';

    function initToasts() {
        var scope = $('.site-main, .admin-content-wrap, .auth-card');
        if (!scope) return;
        var stack = document.createElement('div');
        stack.className = 'toast-stack';
        stack.setAttribute('role', 'status');
        stack.setAttribute('aria-live', 'polite');
        body.appendChild(stack);

        var dismiss = function (a) {
            if (!a.parentNode || a.classList.contains('is-leaving')) return;
            a.classList.add('is-leaving');
            setTimeout(function () { a.remove(); }, 460);
        };

        var toasted = [];
        var norm = function (el) { return (el.textContent || '').replace(/[×\s]+/g, ' ').trim(); };
        var candidates = $$('.site-main .alert, .admin-content-wrap > .alert');
        candidates.forEach(function (a) {
            // Pesan flash yang sama sudah dijadikan toast → jangan tampil dua kali
            if (toasted.indexOf(norm(a)) > -1) { a.remove(); return; }
            var isFlashType = a.matches('.alert-success, .alert-danger, .alert-warning');
            var inContext = a.closest('form, .modal, [data-no-toast]');
            var rich = a.querySelector('ul, ol, form, input, select, textarea, a, button:not(.btn-close)');
            var text = (a.textContent || '').trim();
            if (!isFlashType || inContext || rich || !text || text.length > 240) {
                // Tetap di tempat (konteks penting), cukup masuk dengan slide halus
                a.classList.add('alert-enter');
                return;
            }
            // Ubah menjadi toast
            toasted.push(norm(a));
            a.removeAttribute('data-bs-dismiss');
            a.classList.remove('fade');
            if (!a.querySelector('i.fa, i.fas, i.fa-solid, svg')) {
                var icon = document.createElement('span');
                icon.className = 'toast__icon';
                icon.innerHTML = a.classList.contains('alert-success') ? CHECK_SVG : '<i class="fa fa-exclamation" aria-hidden="true"></i>';
                a.insertBefore(icon, a.firstChild);
            }
            var close = a.querySelector('.btn-close');
            if (!close) {
                close = document.createElement('button');
                close.type = 'button';
                close.className = 'btn-close';
                close.setAttribute('aria-label', 'Tutup');
                a.appendChild(close);
            }
            close.removeAttribute('data-bs-dismiss');
            close.addEventListener('click', function () { dismiss(a); });
            var bar = document.createElement('span');
            bar.className = 'toast__bar';
            bar.style.animationDuration = '5.5s';
            a.appendChild(bar);
            stack.appendChild(a);
            setTimeout(function () { dismiss(a); }, 5500);
        });

        // Dedupe: halaman yang menampilkan flash yang sama di dalam kontennya
        if (toasted.length) {
            $$('.site-main .alert, .admin-content-wrap .alert').forEach(function (a) {
                if (!a.closest('.toast-stack, form, .modal') && toasted.indexOf(norm(a)) > -1) a.remove();
            });
        }
    }

    /* -----------------------------------------------------------------
       13. BADGE STATUS — titik pulse otomatis untuk status "Menunggu"
       ----------------------------------------------------------------- */
    function initBadges() {
        var PENDING = /^(menunggu|pending|menunggu persetujuan|menunggu verifikasi|belum verifikasi|sedang verifikasi|proses|diproses)$/i;
        $$('.badge').forEach(function (b) {
            if (b.classList.contains('status-badge')) return;
            if (PENDING.test((b.textContent || '').replace(/\s+/g, ' ').trim())) b.classList.add('is-pulse');
        });
    }

    /* -----------------------------------------------------------------
       14. CURSOR CUSTOM — lingkaran mengikuti kursor (desktop saja)
       ----------------------------------------------------------------- */
    function initCursor() {
        if (REDUCED || !FINE_POINTER || !DESKTOP) return;
        var dot = document.createElement('div');
        var ring = document.createElement('div');
        dot.className = 'cursor-dot';
        ring.className = 'cursor-ring';
        ring.innerHTML = '<div class="cursor-ring__inner"></div>';
        dot.setAttribute('aria-hidden', 'true');
        ring.setAttribute('aria-hidden', 'true');
        body.appendChild(dot);
        body.appendChild(ring);

        var mx = -100, my = -100, rx = -100, ry = -100, raf = null;
        var loop = function () {
            rx += (mx - rx) * 0.18;
            ry += (my - ry) * 0.18;
            ring.style.transform = 'translate3d(' + rx + 'px,' + ry + 'px,0)';
            raf = (Math.abs(mx - rx) > 0.1 || Math.abs(my - ry) > 0.1) ? requestAnimationFrame(loop) : null;
        };
        document.addEventListener('pointermove', function (e) {
            if (e.pointerType !== 'mouse') return;
            mx = e.clientX; my = e.clientY;
            dot.style.transform = 'translate3d(' + mx + 'px,' + my + 'px,0)';
            root.classList.add('has-cursor');
            if (!raf) raf = requestAnimationFrame(loop);
        }, { passive: true });
        var INTERACTIVE = 'a, button, [role="button"], input, select, textarea, label, summary, .m-card, [data-cursor]';
        document.addEventListener('pointerover', function (e) {
            ring.classList.toggle('is-hover', !!e.target.closest(INTERACTIVE));
        });
        document.addEventListener('pointerdown', function () { ring.classList.add('is-down'); });
        document.addEventListener('pointerup', function () { ring.classList.remove('is-down'); });
        document.documentElement.addEventListener('mouseleave', function () { root.classList.remove('has-cursor'); });
    }

    /* -----------------------------------------------------------------
       15. ADMIN SHELL — topbar ringkas saat scroll
       ----------------------------------------------------------------- */
    function initAdmin() {
        var topbar = $('.admin-topbar');
        if (!topbar) return;
        onScroll(function (y) { topbar.classList.toggle('is-scrolled', y > 8); });
    }

    /* -----------------------------------------------------------------
       15b. APP SHELL (setelah login, body.is-app)
            - auto-reveal semua blok konten & baris tabel (berurutan)
            - sorotan cahaya mengikuti kursor pada kartu kaca
            - tilt 3D kartu statistik
            - parallax latar (scroll + gerak mouse pada aurora)
       ----------------------------------------------------------------- */
    function initAppShell() {
        if (!body.classList.contains('is-app')) return;

        // a) Auto-reveal: blok konten tanpa atribut animasi diberi data-reveal
        if (HAS_GSAP) {
            var SKIP = '.alert, .toast-stack, .modal, script, style, template';
            var blocks = $$('.admin-content-wrap > *, .app-content > * > *').filter(function (el) {
                // (container ber-data-stagger tetap diberi reveal; anak-anaknya diurus stagger)
                return !el.matches(SKIP) && !el.hasAttribute('data-reveal') && !(el.parentElement && el.parentElement.closest('[data-stagger]'));
            });
            blocks.forEach(function (el, i) {
                el.setAttribute('data-reveal', i % 3 === 1 ? 'fade-up' : (i % 3 === 2 ? 'scale' : 'fade-up'));
                el.setAttribute('data-delay', String(Math.min(i * 0.07, 0.42)));
            });
            // Baris tabel muncul berurutan
            $$('.is-app tbody').forEach(function (tb) {
                if (tb.hasAttribute('data-stagger') || tb.children.length > 40 || tb.closest('[data-stagger]')) return;
                tb.setAttribute('data-stagger', 'fade-left');
            });
        }

        // b) Tilt 3D halus pada kartu statistik (desktop)
        $$('.status-card, .stat-card').forEach(function (c) {
            if (!c.hasAttribute('data-tilt')) { c.setAttribute('data-tilt', '6'); c.setAttribute('data-tilt-lift', ''); }
        });

        // c) Sorotan cahaya mengikuti kursor
        if (FINE_POINTER && !REDUCED) {
            var SPOT = '.card, .card-simaksi, .status-card, .stat-card, .dash-card, .quick-action, .gunung-card, .history-table-wrap, .stat-tile, .m-card, .vm-box';
            var pending = null, lastEvt = null;
            document.addEventListener('pointermove', function (e) {
                lastEvt = e;
                if (pending) return;
                pending = requestAnimationFrame(function () {
                    pending = null;
                    var el = lastEvt.target.closest && lastEvt.target.closest(SPOT);
                    if (!el) return;
                    var r = el.getBoundingClientRect();
                    el.style.setProperty('--mx', (lastEvt.clientX - r.left) + 'px');
                    el.style.setProperty('--my', (lastEvt.clientY - r.top) + 'px');
                });
            }, { passive: true });
            document.addEventListener('pointerout', function (e) {
                var el = e.target.closest && e.target.closest(SPOT);
                if (el && !el.contains(e.relatedTarget)) { el.style.setProperty('--mx', '-200px'); el.style.setProperty('--my', '-200px'); }
            });
        }

        // d) Parallax latar: foto bergeser pelan saat scroll, aurora ikut gerak mouse
        if (!REDUCED) {
            var photo = $('[data-app-parallax]');
            if (photo) onScroll(function (y) { photo.style.transform = 'translate3d(0,' + (-Math.min(y * 0.04, 70)) + 'px,0)'; });
            if (FINE_POINTER && DESKTOP) {
                var auroras = $$('.app-bg__aurora');
                var tx = 0, ty = 0, cx = 0, cy = 0, raf2 = null;
                var tick = function () {
                    cx += (tx - cx) * 0.05; cy += (ty - cy) * 0.05;
                    auroras.forEach(function (a) {
                        var d = parseFloat(a.dataset.depth) || 10;
                        a.style.transform = 'translate3d(' + (cx * d) + 'px,' + (cy * d) + 'px,0)';
                    });
                    raf2 = (Math.abs(tx - cx) > 0.001 || Math.abs(ty - cy) > 0.001) ? requestAnimationFrame(tick) : null;
                };
                document.addEventListener('pointermove', function (e) {
                    tx = e.clientX / window.innerWidth - 0.5;
                    ty = e.clientY / window.innerHeight - 0.5;
                    if (!raf2) raf2 = requestAnimationFrame(tick);
                }, { passive: true });
            }
        }
    }

    /* -----------------------------------------------------------------
       16. INIT
       ----------------------------------------------------------------- */
    function init() {
        var introDelay = initPreloader();
        initNavbar(introDelay);
        initScrollUI();
        initHero(introDelay);
        initAppShell();
        initReveal();
        initCounters();
        initSteps();
        initParallax();
        initTilt();
        initSkeletons();
        initRipple();
        initForms();
        initToasts();
        initBadges();
        initCursor();
        initAdmin();

        if (HAS_GSAP) {
            // Hitung ulang posisi trigger setelah gambar & font termuat
            window.addEventListener('load', function () { ScrollTrigger.refresh(); });
        }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
