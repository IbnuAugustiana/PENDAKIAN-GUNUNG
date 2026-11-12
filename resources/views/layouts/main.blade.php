<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAKSI.COM')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Tema, font, ikon & deteksi animasi (design tokens ada di public/css/simaksi-theme.css) --}}
    @include('partials.ui.head')

    @stack('styles')
</head>
<body class="ui @yield('body_class') @auth @unless(request()->routeIs('landing')) is-app @endunless @endauth">

    @include('partials.ui.body-start')

    {{-- Latar aplikasi mewah (khusus setelah login) --}}
    @auth @unless(request()->routeIs('landing')) @include('partials.ui.app-bg') @endunless @endauth

    <!-- ================= NAVBAR ================= -->
    <nav class="site-nav" aria-label="Navigasi utama">
        <div class="site-nav__inner">
            <a href="{{ url('/') }}" class="site-nav__brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <span>SIMAKSI<b>.COM</b></span>
            </a>

            <div class="site-nav__menu">
                <ul class="nav-links">
                    <li>
                        @auth
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="{{ request()->is('user/dashboard') || request()->is('admin/dashboard') || request()->is('dashboard') ? 'active' : '' }}">Home</a>
                        @else
                            <a href="{{ route('landing') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
                        @endauth
                    </li>
                    <li><a href="{{ route('gunung.index') }}" class="{{ request()->is('gunung') ? 'active' : '' }}">Gunung</a></li>
                    <li><a href="{{ route('rute_pendakian.index') }}" class="{{ request()->is('rute') || request()->is('rute_pendakian') ? 'active' : '' }}">Rute</a></li>
                    <li><a href="{{ route('panduan') }}" class="{{ request()->is('panduan') ? 'active' : '' }}">Panduan</a></li>
                    <li><a href="{{ route('tentang') }}" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a></li>
                    @auth
                        <li><a href="{{ route('pendaftaran.index') }}" class="{{ request()->is('pendaftaran*') ? 'active' : '' }}">Isi Formulir SIMAKSI</a></li>
                    @endauth
                </ul>
            </div>

            <div class="site-nav__actions">
                <form action="{{ route('gunung.search') }}" method="GET" class="m-0" data-search-form role="search">
                    <div class="nav-search">
                        <i class="fas fa-search search-icon" aria-hidden="true"></i>
                        <input id="navbarSearchInput" type="text" name="q" value="{{ request()->get('q') ?? '' }}" placeholder="Cari gunung..." autocomplete="off" data-search-input maxlength="100" aria-label="Cari gunung">
                        <button id="navbarSearchClear" type="button" class="btn-clear-search" aria-label="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                        <button type="submit" class="btn-search" aria-label="Cari">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>

                @auth
                    <div class="nav-user">
                        <div class="nav-user__avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <div class="nav-user__meta">
                            <div class="nav-user__name">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                            <div class="nav-user__role">Pendaki</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0" data-confirm-message="Yakin ingin logout dari akun ini?" data-confirm-title="Konfirmasi Logout" data-confirm-ok="Ya, logout">
                        @csrf
                        <button type="submit" class="btn btn-simaksi-danger"><i class="fas fa-sign-out-alt me-1"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-nav-soft"><i class="fas fa-sign-in-alt me-1"></i> Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-ui--sun btn"><i class="fas fa-user-plus me-1"></i> Daftar</a>
                @endauth
            </div>

            <button class="nav-burger" type="button" data-nav-toggle aria-controls="navDrawer" aria-expanded="false" aria-label="Buka menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ============ DRAWER MOBILE ============ -->
    <div class="nav-drawer-overlay" data-nav-overlay></div>
    <aside class="nav-drawer" id="navDrawer" aria-hidden="true" aria-label="Menu">
        <ul class="drawer-links">
            <li>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="{{ request()->is('user/dashboard') || request()->is('admin/dashboard') || request()->is('dashboard') ? 'active' : '' }}"><i class="fa fa-house"></i> Home</a>
                @else
                    <a href="{{ route('landing') }}" class="{{ request()->is('/') ? 'active' : '' }}"><i class="fa fa-house"></i> Home</a>
                @endauth
            </li>
            <li><a href="{{ route('gunung.index') }}" class="{{ request()->is('gunung') ? 'active' : '' }}"><i class="fa fa-mountain"></i> Gunung</a></li>
            <li><a href="{{ route('rute_pendakian.index') }}" class="{{ request()->is('rute') || request()->is('rute_pendakian') ? 'active' : '' }}"><i class="fa fa-route"></i> Rute</a></li>
            <li><a href="{{ route('panduan') }}" class="{{ request()->is('panduan') ? 'active' : '' }}"><i class="fa fa-compass"></i> Panduan</a></li>
            <li><a href="{{ route('tentang') }}" class="{{ request()->is('tentang') ? 'active' : '' }}"><i class="fa fa-circle-info"></i> Tentang</a></li>
            @auth
                <li><a href="{{ route('pendaftaran.index') }}" class="{{ request()->is('pendaftaran*') ? 'active' : '' }}"><i class="fa fa-file-signature"></i> Isi Formulir SIMAKSI</a></li>
            @else
                <li><a href="{{ route('login') }}"><i class="fa fa-file-signature"></i> Isi Formulir SIMAKSI</a></li>
            @endauth
        </ul>

        <div class="drawer-block">
            <form action="{{ route('gunung.search') }}" method="GET" data-search-form role="search">
                <div class="nav-search">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                    <input type="text" name="q" value="{{ request()->get('q') ?? '' }}" placeholder="Cari gunung..." autocomplete="off" data-search-input maxlength="100" aria-label="Cari gunung">
                    <button type="submit" class="btn-search" aria-label="Cari">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>

            <div class="drawer-actions">
                @auth
                    <div class="nav-user">
                        <div class="nav-user__avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <div class="nav-user__meta">
                            <div class="nav-user__name">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                            <div class="nav-user__role">Pendaki</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0" data-confirm-message="Yakin ingin logout dari akun ini?" data-confirm-title="Konfirmasi Logout" data-confirm-ok="Ya, logout">
                        @csrf
                        <button type="submit" class="btn btn-simaksi-danger w-100"> <i class="fas fa-sign-out-alt me-1"></i> Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-nav-soft w-100"><i class="fas fa-sign-in-alt me-1"></i> Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-ui--sun w-100"><i class="fas fa-user-plus me-1"></i> Daftar</a>
                @endauth
            </div>
        </div>
    </aside>

    <!-- ================= CONTENT ================= -->
    <main id="main-content" class="site-main">
        @yield('hero')
        <div class="app-content">
            @yield('content')
        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="site-footer">
        <div class="site-footer__ridge" aria-hidden="true">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#04170F" d="M0 80 L0 52 L120 30 L210 46 L330 12 L430 40 L560 6 L680 44 L800 22 L930 50 L1060 14 L1190 42 L1310 24 L1440 48 L1440 80Z"/>
            </svg>
        </div>
        <div class="site-footer__inner">
            <div class="row g-4">
                <div class="col-12 col-lg-4" data-reveal="fade-up">
                    <a href="{{ url('/') }}" class="site-footer__brand"><img src="{{ asset('images/logo.png') }}" alt="Logo"> SIMAKSI.COM</a>
                    <p class="site-footer__desc">Platform pengelolaan perizinan pendakian gunung yang modern, rapi, dan mudah dipantau.</p>
                    <p class="site-footer__credit">Dibuat oleh <a href="https://github.com/IbnuAugustiana" target="_blank" rel="noopener">Ibnu Augustiana</a></p>
                    <div class="site-footer__social">
                        <a href="mailto:info@simaksi.com" aria-label="Email"><i class="fa fa-envelope"></i></a>
                        <a href="https://github.com/IbnuAugustiana/PENDAKIAN-GUNUNG" target="_blank" rel="noopener" aria-label="Repository GitHub PENDAKIAN-GUNUNG"><i class="fa-brands fa-github"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2" data-reveal="fade-up" data-delay=".05">
                    <h4>Jelajahi</h4>
                    <ul>
                        <li><a href="{{ route('gunung.index') }}">Daftar Gunung</a></li>
                        <li><a href="{{ route('rute_pendakian.index') }}">Rute Resmi</a></li>
                        <li><a href="{{ route('panduan') }}">Panduan</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2" data-reveal="fade-up" data-delay=".1">
                    <h4>Tentang</h4>
                    <ul>
                        <li><a href="{{ route('tentang') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('panduan') }}">Cara Mendaftar</a></li>
                        <li><a href="{{ route('panduan') }}">Keselamatan</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2" data-reveal="fade-up" data-delay=".15">
                    <h4>Akun</h4>
                    <ul>
                        @auth
                            <li><a href="{{ route('pendaftaran.index') }}">Formulir SIMAKSI</a></li>
                            <li><a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Masuk</a></li>
                            <li><a href="{{ route('register') }}">Daftar</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-6 col-lg-2" data-reveal="fade-up" data-delay=".2">
                    <h4>Kontak</h4>
                    <ul>
                        <li><a href="mailto:info@simaksi.com">info@simaksi.com</a></li>
                        <li><a href="https://github.com/IbnuAugustiana/PENDAKIAN-GUNUNG" target="_blank" rel="noopener"><i class="fa-brands fa-github"></i> Repository</a></li>
                    </ul>
                </div>
            </div>
            <div class="site-footer__bottom">
                <span>&copy; {{ date('Y') }} SIMAKSI.COM — Jelajahi Alam Indonesia dengan Aman 🌲</span>
                <span>Dibuat oleh <a href="https://github.com/IbnuAugustiana" target="_blank" rel="noopener">Ibnu Augustiana</a> untuk pendaki Indonesia</span>
            </div>
        </div>
    </footer>

    {{-- ✅ Bootstrap JS untuk Modal & Components --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        window.notify = function(type, title, text, options = {}) {
            const base = {
                icon: type,
                title: title || '',
                text: text || '',
                confirmButtonColor: '#15803D'
            };
            return Swal.fire(Object.assign(base, options));
        };
        window.toast = function(type, title, timer = 2600) {
            return Swal.fire({ toast: true, position: 'top-end', icon: type, title: title, showConfirmButton: false, timer: timer, timerProgressBar: true });
        };
    </script>
    <script>
        (function(){
            const originalAlert = window.alert;
            window.alert = function(message){
                try {
                    const msg = String(message || '');
                    const lower = msg.toLowerCase();
                    let icon = 'info';
                    if (lower.includes('berhasil') || lower.includes('success')) icon = 'success';
                    else if (lower.includes('gagal') || lower.includes('error') || lower.includes('kesalahan')) icon = 'error';
                    else if (lower.includes('tutup') || lower.includes('warning') || lower.includes('peringatan')) icon = 'warning';
                    Swal.fire({ icon, title: msg, confirmButtonColor: '#15803D' });
                } catch (e) { originalAlert(message); }
            };
        })();
    </script>

    {{-- Custom Scripts dari halaman child --}}
    @stack('scripts')

    {{-- Script untuk smooth modal animation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth animation untuk semua modal
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    document.body.style.overflow = 'hidden';
                });

                modal.addEventListener('hidden.bs.modal', function() {
                    document.body.style.overflow = 'auto';
                });
            });

            // Auto hide alerts setelah 5 detik.
            // Bila animations.js aktif, pesan flash ditangani sebagai toast (slide + auto-hide).
            if (!window.SIMAKSI_ANIM_READY) {
                const alerts = document.querySelectorAll('.alert-success, .alert-danger, .alert-warning');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }, 5000);
                });
            }

            // Search clear button logic
            const input = document.getElementById('navbarSearchInput');
            const clearBtn = document.getElementById('navbarSearchClear');
            function updateClear(){ if(clearBtn){ clearBtn.style.display = input && input.value ? 'inline-flex' : 'none'; } }
            if (input){
                updateClear();
                input.addEventListener('input', updateClear);
                input.addEventListener('keydown', (e)=>{ if(e.key==='Escape'){ input.value=''; input.focus(); updateClear(); }});
            }
            if (clearBtn){
                clearBtn.addEventListener('click', ()=>{ input.value=''; input.focus(); updateClear(); });
            }
        });
    </script>
    <script>
        (function () {
            function normalizeSpaces(value) {
                return String(value || '').replace(/\s+/g, ' ').trim();
            }

            function setInvalid(el, message) {
                if (!el) return;
                el.classList.add('is-invalid');
                el.setAttribute('aria-invalid', 'true');
                el.setCustomValidity(message || 'Input tidak valid');

                let feedback = el.parentElement ? el.parentElement.querySelector(`.dynamic-feedback[data-for="${el.name || el.id || ''}"]`) : null;
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback dynamic-feedback';
                    feedback.dataset.for = el.name || el.id || '';
                    el.insertAdjacentElement('afterend', feedback);
                }
                feedback.textContent = message || 'Input tidak valid';
            }

            function clearInvalid(el) {
                if (!el) return;
                el.classList.remove('is-invalid');
                el.removeAttribute('aria-invalid');
                el.setCustomValidity('');
                const feedback = el.parentElement ? el.parentElement.querySelector(`.dynamic-feedback[data-for="${el.name || el.id || ''}"]`) : null;
                if (feedback) feedback.remove();
            }

            function validateName(el) {
                const raw = String(el.value || '');
                const cleaned = raw.replace(/[^A-Za-z\s'.-]/g, '').replace(/\s{2,}/g, ' ');
                if (cleaned !== raw) el.value = cleaned;
                const value = normalizeSpaces(el.value);
                el.value = value;

                if (!value) return setInvalid(el, 'Nama wajib diisi.'), false;
                if (value.length < 3) return setInvalid(el, 'Nama minimal 3 karakter.'), false;
                if (/^\d+$/.test(value)) return setInvalid(el, 'Nama tidak boleh hanya angka.'), false;
                if (!/[A-Za-z]/.test(value)) return setInvalid(el, 'Nama harus mengandung huruf.'), false;
                clearInvalid(el);
                return true;
            }

            function validatePhone(el) {
                const digits = String(el.value || '').replace(/\D/g, '').slice(0, 15);
                el.value = digits;
                if (!digits) return setInvalid(el, 'Nomor HP wajib diisi.'), false;
                if (digits.length < 10 || digits.length > 15) return setInvalid(el, 'Nomor HP harus 10-15 digit angka.'), false;
                clearInvalid(el);
                return true;
            }

            function validateAge(el) {
                const digits = String(el.value || '').replace(/\D/g, '');
                el.value = digits;
                const value = parseInt(digits || '0', 10);
                if (!digits) return setInvalid(el, 'Usia wajib diisi.'), false;
                if (value < 1 || value > 100) return setInvalid(el, 'Usia harus antara 1 sampai 100 tahun.'), false;
                clearInvalid(el);
                return true;
            }

            function validatePositiveInt(el, min = 1, allowZero = false) {
                const digits = String(el.value || '').replace(/\D/g, '');
                el.value = digits;
                if (!digits && !allowZero) return setInvalid(el, 'Field ini wajib diisi.'), false;
                if (!digits && allowZero) return clearInvalid(el), true;
                const value = parseInt(digits, 10);
                if (!allowZero && value < min) return setInvalid(el, `Nilai minimal ${min}.`), false;
                if (allowZero && value < 0) return setInvalid(el, 'Nilai tidak boleh negatif.'), false;
                clearInvalid(el);
                return true;
            }

            function validateEmail(el) {
                const value = String(el.value || '').replace(/\s+/g, '');
                el.value = value;
                if (!value) return setInvalid(el, 'Email wajib diisi.'), false;
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return setInvalid(el, 'Format email tidak valid.'), false;
                clearInvalid(el);
                return true;
            }

            function validateIdentity(el) {
                const selector = el.dataset.identityTypeTarget;
                const typeEl = selector ? document.querySelector(selector) : null;
                const identityType = (typeEl ? typeEl.value : '').toUpperCase();
                const raw = String(el.value || '').toUpperCase().replace(/\s+/g, '');
                const cleaned = identityType === 'KTP' || identityType === 'KK'
                    ? raw.replace(/\D/g, '').slice(0, 16)
                    : raw.replace(/[^A-Z0-9]/g, '').slice(0, 20);
                el.value = cleaned;

                if (!cleaned) return setInvalid(el, 'Nomor identitas wajib diisi.'), false;
                if (identityType === 'KTP' || identityType === 'KK') {
                    if (!/^\d{16}$/.test(cleaned)) return setInvalid(el, `Nomor identitas untuk ${identityType} wajib tepat 16 digit angka.`), false;
                } else if (identityType === 'SIM') {
                    if (!/^[A-Z0-9]{5,20}$/.test(cleaned)) return setInvalid(el, 'Nomor SIM hanya boleh huruf/angka dengan panjang 5-20 karakter.'), false;
                } else if (!/^[A-Z0-9]{4,20}$/.test(cleaned)) {
                    return setInvalid(el, 'Nomor identitas hanya boleh huruf/angka dengan panjang wajar.'), false;
                }

                clearInvalid(el);
                return true;
            }

            function validateFile(el) {
                if (!el.files || !el.files.length) {
                    clearInvalid(el);
                    return true;
                }
                const allowed = (el.dataset.allowedExt || '').split(',').map(v => v.trim().toLowerCase()).filter(Boolean);
                if (!allowed.length) {
                    clearInvalid(el);
                    return true;
                }
                const fileName = el.files[0].name.toLowerCase();
                const ext = fileName.includes('.') ? fileName.split('.').pop() : '';
                if (!allowed.includes(ext)) return setInvalid(el, `File harus berformat: ${allowed.join(', ')}.`), false;
                clearInvalid(el);
                return true;
            }

            function sanitizeSearchValue(value) {
                return normalizeSpaces(String(value || '').replace(/[<>{}[\]^`;$\\|]/g, ''));
            }

            function validateField(el) {
                const type = el.dataset.validate;
                if (!type) return true;
                if (type === 'name') return validateName(el);
                if (type === 'phone') return validatePhone(el);
                if (type === 'age') return validateAge(el);
                if (type === 'email') return validateEmail(el);
                if (type === 'identity') return validateIdentity(el);
                if (type === 'quantity') return validatePositiveInt(el, 1, false);
                if (type === 'price') return validatePositiveInt(el, 0, true);
                if (type === 'file') return validateFile(el);
                return true;
            }

            document.addEventListener('input', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement || el instanceof HTMLSelectElement)) return;
                if (el.dataset.validate) validateField(el);
            });

            document.addEventListener('change', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement || el instanceof HTMLSelectElement)) return;
                if (el.dataset.validate) validateField(el);
                if (el.matches('[data-identity-type-source]')) {
                    const target = document.querySelector(el.dataset.identityTypeSource);
                    if (target) validateIdentity(target);
                }
            });

            document.addEventListener('input', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement)) return;
                if (el.type === 'search' || el.hasAttribute('data-search-input')) {
                    el.value = sanitizeSearchValue(el.value);
                }
            });

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;

                const searchInput = form.querySelector('[data-search-input]');
                if (searchInput) {
                    searchInput.value = sanitizeSearchValue(searchInput.value);
                    if (!searchInput.value) {
                        e.preventDefault();
                        Swal.fire({ icon: 'info', title: 'Masukkan kata kunci pencarian terlebih dahulu.', confirmButtonColor: '#15803D' });
                        return;
                    }
                }

                let invalid = false;
                form.querySelectorAll('[data-validate]').forEach((field) => {
                    if (!validateField(field) && !invalid) {
                        invalid = true;
                        field.focus();
                    }
                });
                if (invalid) {
                    e.preventDefault();
                    Swal.fire({ icon: 'warning', title: 'Periksa kembali input yang masih tidak valid.', confirmButtonColor: '#15803D' });
                    return;
                }

                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }

                const confirmMessage = form.dataset.confirmMessage;
                if (confirmMessage) {
                    e.preventDefault();
                    Swal.fire({
                        icon: form.dataset.confirmIcon || 'question',
                        title: form.dataset.confirmTitle || 'Konfirmasi',
                        text: confirmMessage,
                        showCancelButton: true,
                        confirmButtonText: form.dataset.confirmOk || 'Ya, lanjutkan',
                        cancelButtonText: form.dataset.confirmCancel || 'Batal',
                        confirmButtonColor: '#15803D'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = 'true';
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                    });
                }
            }, true);
        })();
    </script>
<script>
document.addEventListener('hidden.bs.modal', function () {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = 'auto';
});
</script>

    {{-- GSAP + animations.js (public/js/animations.js) --}}
    @include('partials.ui.scripts')
</body>
</html>
