{{-- Judul section: eyebrow + judul + sub. align="center" untuk rata tengah. --}}
@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'left', 'reveal' => 'fade-up'])
<div {{ $attributes->merge(['class' => 'section-heading' . ($align === 'center' ? ' section-heading--center' : '')]) }} data-reveal="{{ $reveal }}">
    @if($eyebrow)<div class="eyebrow">{{ $eyebrow }}</div>@endif
    <h2 class="section-title">{{ $title }}</h2>
    @if($subtitle)<p class="section-sub">{{ $subtitle }}</p>@endif
</div>
