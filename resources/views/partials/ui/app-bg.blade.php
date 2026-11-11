{{-- =====================================================================
     LATAR APLIKASI (khusus setelah login: halaman user & admin)
     Foto gunung + overlay transparan, aurora bergerak, kabut, partikel
     kunang-kunang, dan siluet gunung. Semua animasi transform/opacity.
     ===================================================================== --}}
<div class="app-bg" aria-hidden="true">
    <div class="app-bg__photo" data-app-parallax>
        <img src="{{ asset('images/gunung/arjuno.jpg') }}" alt="">
    </div>
    <div class="app-bg__veil"></div>
    <span class="app-bg__aurora app-bg__aurora--1" data-depth="18"></span>
    <span class="app-bg__aurora app-bg__aurora--2" data-depth="-24"></span>
    <span class="app-bg__aurora app-bg__aurora--3" data-depth="12"></span>
    <span class="app-bg__mist app-bg__mist--1"></span>
    <span class="app-bg__mist app-bg__mist--2"></span>
    <div class="app-bg__particles">
        @for($i = 1; $i <= 18; $i++)<i style="--i:{{ $i }}"></i>@endfor
    </div>
    <svg class="app-bg__ridge" viewBox="0 0 1440 220" preserveAspectRatio="none">
        <path fill="rgba(20,104,63,.10)" d="M0 150 L120 96 L230 132 L360 60 L470 118 L600 40 L720 120 L840 74 L960 136 L1090 58 L1210 120 L1330 84 L1440 120 L1440 220 L0 220Z"/>
        <path fill="rgba(11,61,44,.12)" d="M0 190 L110 150 L230 182 L350 132 L480 178 L610 120 L740 182 L860 146 L990 186 L1120 132 L1250 176 L1360 150 L1440 176 L1440 220 L0 220Z"/>
    </svg>
</div>
