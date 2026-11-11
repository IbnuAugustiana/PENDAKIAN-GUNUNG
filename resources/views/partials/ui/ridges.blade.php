{{-- Siluet gunung berlapis (dipakai hero, page header, footer).
     $variant: 'hero' | 'header'  — warna lapisan terdekat menyatu dengan latar halaman. --}}
@php $variant = $variant ?? 'header'; @endphp
<svg class="{{ $variant === 'hero' ? 'hero__layer-far' : '' }}" viewBox="0 0 1440 320" preserveAspectRatio="none" aria-hidden="true" @if($variant !== 'hero') data-parallax="10" @endif>
    <path class="hero__layer--far" fill="rgba(255,255,255,.08)" d="M0 230 L120 168 L210 204 L330 118 L420 176 L540 92 L650 180 L760 128 L880 200 L990 108 L1110 186 L1220 138 L1340 200 L1440 160 L1440 320 L0 320Z"/>
</svg>
<svg class="{{ $variant === 'hero' ? 'hero__layer-mid' : '' }}" viewBox="0 0 1440 320" preserveAspectRatio="none" aria-hidden="true" @if($variant !== 'hero') data-parallax="5" @endif>
    <path class="hero__layer--mid" fill="rgba(4,23,15,.35)" d="M0 262 L90 214 L200 250 L310 188 L430 246 L560 172 L690 250 L800 204 L930 256 L1060 182 L1180 240 L1300 198 L1440 250 L1440 320 L0 320Z"/>
</svg>
@if($variant === 'hero')
<svg viewBox="0 0 1440 320" preserveAspectRatio="none" aria-hidden="true">
    <path class="hero__layer--near" d="M0 292 C120 272 220 302 360 284 C500 266 600 298 760 286 C920 274 1040 302 1200 288 C1300 280 1380 290 1440 286 L1440 321 L0 321Z"/>
</svg>
@endif
