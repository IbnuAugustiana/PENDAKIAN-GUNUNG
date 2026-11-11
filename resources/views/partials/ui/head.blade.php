{{-- =====================================================================
     UI HEAD — font, ikon, tema, dan script deteksi awal.
     Dipakai oleh: layouts.main, admin.layouts.app, auth/login, auth/register.
     Taruh SETELAH Bootstrap CSS (jika ada) agar override tema berlaku.
     ===================================================================== --}}
<meta name="theme-color" content="#0B3D2C">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/simaksi-theme.css') }}?v={{ @filemtime(public_path('css/simaksi-theme.css')) }}">
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

{{-- Deteksi awal (sebelum render) agar tidak ada kedipan konten:
     - .anim      : animasi aktif (dicabut bila reduced-motion / GSAP gagal)
     - .no-vt     : browser tanpa View Transitions → pakai fade-in cadangan
     - .intro-seen: preloader hanya tampil sekali per sesi --}}
<script>
    (function (d, w) {
        var r = d.documentElement;
        r.classList.add('js');
        var reduce = w.matchMedia && w.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!reduce) r.classList.add('anim');
        if (!w.CSSViewTransitionRule) r.classList.add('no-vt');
        try {
            if (w.sessionStorage.getItem('simaksi-intro')) r.classList.add('intro-seen');
            else w.sessionStorage.setItem('simaksi-intro', '1');
        } catch (e) { r.classList.add('intro-seen'); }
        // View Transitions: transisi yang dibatalkan (mis. klik cepat saat transisi
        // berjalan) menolak promise-nya — tangkap agar tidak jadi error di console.
        var quiet = function (e) {
            var vt = e.viewTransition;
            if (!vt) return;
            ['ready', 'finished', 'updateCallbackDone'].forEach(function (k) { if (vt[k] && vt[k].catch) vt[k].catch(function () {}); });
        };
        w.addEventListener('pagereveal', quiet);
        w.addEventListener('pageswap', quiet);
        // Pengaman: bila animations.js tidak termuat, tampilkan semua konten
        setTimeout(function () { if (!w.SIMAKSI_ANIM_READY) r.classList.remove('anim'); }, 4000);
    })(document, window);
</script>
