@extends('layouts.main')

@section('title', 'Rute Pendakian Resmi - SIMAKSI')

@section('content')
<section class="container">
    <x-ui.page-header eyebrow="Jalur Resmi" icon="fa-route" title="Rute Pendakian Resmi"
        subtitle="Jalur pendakian resmi untuk keamanan dan kelestarian alam."
        :image="asset('images/rute/semeru_ranupani.png')" :crumbs="['Rute' => null]">
        <span class="chip"><i class="fa fa-route"></i> {{ $rutes->count() }} jalur tersedia</span>
    </x-ui.page-header>

    @if($rutes->isEmpty())
        <div class="card-simaksi p-5 text-center" data-reveal="scale">
            <div class="stat-tile__icon mx-auto"><i class="fa fa-route"></i></div>
            <div class="fw-bold mb-1">Tidak ada data rute pendakian</div>
            <div class="text-muted" style="font-size:.95rem;">Coba kunjungi gunung lainnya.</div>
        </div>
    @else
        <div class="row g-3 g-lg-4" data-stagger>
            @foreach($rutes as $r)
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="m-card" data-tilt="4" data-tilt-lift>
                        <div class="m-card__media skeleton" style="aspect-ratio:16/10;">
                            <img src="{{ optional($r->gunung)->gambar ? asset('images/gunung/' . $r->gunung->gambar) : asset('images/placeholder-mountain.svg') }}" alt="{{ $r->nama_rute }}" loading="lazy"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-mountain.svg') }}';">
                            <div class="m-card__tags">
                                <span class="m-tag"><i class="fa fa-mountain"></i>{{ $r->gunung->nama_gunung ?? 'Gunung Tidak Diketahui' }}</span>
                                <span class="m-tag m-tag--price"><i class="fa fa-tag"></i> Rp {{ number_format($r->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="m-card__body">
                            <h2 class="m-card__name">{{ $r->nama_rute }}</h2>

                            <div class="text-muted" style="line-height:1.7; font-size:.92rem;">
                                <div class="d-flex align-items-start gap-2 mb-1">
                                    <i class="fa fa-tag text-green" style="margin-top:4px;"></i>
                                    <span><strong>Harga:</strong> Rp {{ number_format($r->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fa fa-info-circle text-green" style="margin-top:4px;"></i>
                                    <span><strong>Deskripsi:</strong> {{ $r->deskripsi ?? 'Tidak ada deskripsi.' }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
