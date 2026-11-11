{{-- Header halaman interior: banner hutan + siluet gunung parallax.
     Pemakaian:
     <x-ui.page-header eyebrow="Destinasi" icon="fa-mountain" title="Daftar Gunung" subtitle="..." :image="asset('...')">
         (slot: chip / tombol tambahan)
     </x-ui.page-header> --}}
@props([
    'eyebrow'  => null,
    'icon'     => 'fa-mountain-sun',
    'title',
    'subtitle' => null,
    'image'    => null,
    'crumbs'   => [],
])
<header {{ $attributes->merge(['class' => 'page-header']) }}>
    @if($image)
        <div class="page-header__media" aria-hidden="true">
            <img src="{{ $image }}" alt="" data-parallax="14" loading="eager">
        </div>
    @endif
    <div class="page-header__layers" aria-hidden="true">
        @include('partials.ui.ridges', ['variant' => 'header'])
    </div>

    @if(!empty($crumbs))
        <nav class="page-header__crumb" aria-label="Breadcrumb" data-reveal="fade-down">
            <a href="{{ url('/') }}"><i class="fa fa-house" aria-hidden="true"></i></a>
            @foreach($crumbs as $label => $href)
                <i class="fa fa-chevron-right" style="font-size:.6rem;" aria-hidden="true"></i>
                @if($href)<a href="{{ $href }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif
            @endforeach
        </nav>
    @endif

    @if($eyebrow)
        <div class="page-header__eyebrow" data-reveal="fade-up"><i class="fa {{ $icon }}" aria-hidden="true"></i> {{ $eyebrow }}</div>
    @endif
    <h1 class="page-header__title" data-reveal="fade-up" data-delay=".08">{{ $title }}</h1>
    @if($subtitle)
        <p class="page-header__sub" data-reveal="fade-up" data-delay=".16">{{ $subtitle }}</p>
    @endif
    @if(trim($slot) !== '')
        <div class="page-header__extra" data-reveal="fade-up" data-delay=".24">{{ $slot }}</div>
    @endif
</header>
