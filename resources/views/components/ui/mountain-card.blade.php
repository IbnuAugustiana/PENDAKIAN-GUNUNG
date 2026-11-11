{{-- Kartu gunung: gambar (skeleton + zoom), nama, lokasi, ketinggian (mdpl),
     harga, overlay info dari bawah, tilt 3D halus (desktop).
     Semua data berasal dari variabel yang SUDAH ada di backend.
     Slot "actions" untuk tombol (Lihat Rute, Maps, dll). --}}
@props([
    'href'        => null,
    'image'       => null,
    'name',
    'location'    => null,
    'height'      => null,   // ketinggian (angka mdpl)
    'price'       => null,   // harga SIMAKSI (angka)
    'description' => null,
    'tag'         => null,   // label kecil kiri atas (mis. "Populer")
    'excerpt'     => null,   // ringkasan yang selalu tampil di badan kartu
])
@php
    $fallback = asset('images/placeholder-mountain.svg');
@endphp
<article {{ $attributes->merge(['class' => 'm-card']) }} data-tilt="5" data-tilt-lift>
    <div class="m-card__media skeleton">
        <img src="{{ $image ?: $fallback }}" alt="{{ $name }}" loading="lazy" decoding="async"
             onerror="this.onerror=null;this.src='{{ $fallback }}';">
        <div class="m-card__tags">
            @if($tag)
                <span class="m-tag"><i class="fa fa-fire" aria-hidden="true"></i> {{ $tag }}</span>
            @else
                <span></span>
            @endif
            @if(!is_null($price))
                <span class="m-tag m-tag--price"><i class="fa fa-ticket" aria-hidden="true"></i> Rp {{ number_format($price, 0, ',', '.') }}</span>
            @endif
        </div>
        @if($height)
            <div class="m-card__over">
                <div class="alt">{{ number_format($height, 0, ',', '.') }}<small>mdpl</small></div>
            </div>
        @endif
        @if($description)
            <div class="m-card__reveal" aria-hidden="true">{{ \Illuminate\Support\Str::limit($description, 140) }}</div>
        @endif
    </div>
    <div class="m-card__body">
        <h3 class="m-card__name">{{ $name }}</h3>
        @if($location)
            <div class="m-card__loc"><i class="fa fa-location-dot" aria-hidden="true"></i> {{ $location }}</div>
        @endif
        @if($excerpt)
            <p class="m-card__desc">{{ $excerpt }}</p>
        @endif
        @if(isset($actions) && trim($actions) !== '')
            <div class="m-card__actions">{{ $actions }}</div>
        @endif
    </div>
    @if($href)
        <a href="{{ $href }}" class="m-card__link" aria-label="Lihat {{ $name }}"></a>
    @endif
</article>
