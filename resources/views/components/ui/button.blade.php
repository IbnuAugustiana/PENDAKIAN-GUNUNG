{{-- Tombol UI: <a> bila ada href, selain itu <button>.
     variant: primary | outline | soft | glass | sun
     <x-ui.button href="{{ route('register') }}" icon="fa-file-signature" variant="primary">Daftar</x-ui.button> --}}
@props(['href' => null, 'variant' => 'primary', 'icon' => null, 'iconRight' => null, 'type' => 'button', 'size' => null])
@php
    $classes = 'btn btn-ui btn-ui--' . $variant . ($size ? ' btn-' . $size : '');
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="fa {{ $icon }} me-2" aria-hidden="true"></i>@endif<span>{{ $slot }}</span>@if($iconRight)<i class="fa {{ $iconRight }} ms-2" aria-hidden="true"></i>@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="fa {{ $icon }} me-2" aria-hidden="true"></i>@endif<span>{{ $slot }}</span>@if($iconRight)<i class="fa {{ $iconRight }} ms-2" aria-hidden="true"></i>@endif
    </button>
@endif
