{{-- UI SCRIPTS — tombol kembali ke atas + GSAP & animations.js.
     GSAP 3.13 (+ScrollTrigger) disimpan lokal di public/vendor/gsap agar tidak
     bergantung pada CDN pihak ketiga (lebih cepat & andal saat di-hosting).
     defer: tidak memblokir render, urutan eksekusi tetap terjaga. --}}
<button type="button" class="to-top" aria-label="Kembali ke atas">
    <svg viewBox="0 0 54 54" aria-hidden="true"><circle cx="27" cy="27" r="25"/></svg>
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
</button>

<script defer src="{{ asset('vendor/gsap/gsap.min.js') }}"></script>
<script defer src="{{ asset('vendor/gsap/ScrollTrigger.min.js') }}"></script>
<script defer src="{{ asset('js/animations.js') }}?v={{ @filemtime(public_path('js/animations.js')) }}"></script>
