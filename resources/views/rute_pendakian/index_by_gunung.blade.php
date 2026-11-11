@extends('layouts.main')

@section('title', 'Rute Pendakian - SIMAKSI')

@section('content')
<section class="container">
    <x-ui.page-header eyebrow="Rute Pendakian" icon="fa-route" :title="'Rute Pendakian ' . $gunung->nama_gunung"
        subtitle="Pilih jalur resmi pendakian untuk keamanan dan kenyamanan."
        :image="$gunung->gambar ? asset('images/gunung/' . $gunung->gambar) : null"
        :crumbs="['Gunung' => route('gunung.index'), $gunung->nama_gunung => null]">
        <span class="chip"><i class="fa fa-location-dot"></i> {{ $gunung->lokasi }}</span>
        <span class="chip"><i class="fa fa-mountain"></i> {{ number_format($gunung->ketinggian, 0, ',', '.') }} mdpl</span>
        <span class="chip"><i class="fa fa-route"></i> {{ $rutes->count() }} jalur</span>
    </x-ui.page-header>

    @if($gunung->latitude && $gunung->longitude)
        <div class="card-simaksi p-4 mb-4" data-reveal="fade-up">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-map"></i></span>
                <h2 class="h5 fw-bold mb-0">Lokasi Gunung {{ $gunung->nama_gunung }} di Peta</h2>
            </div>
            <div id="map" class="w-100" style="height:420px; border-radius:16px; border:1px solid var(--c-line);"></div>
        </div>
    @endif

    <div class="row g-3 g-lg-4" data-stagger>
        @forelse($rutes as $r)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="m-card" data-tilt="4" data-tilt-lift>
                    <div class="m-card__media skeleton" style="aspect-ratio:16/10;">
                        <img src="{{ $gunung->gambar ? asset('images/gunung/' . $gunung->gambar) : asset('images/placeholder-mountain.svg') }}" alt="{{ $r->nama_rute }}" loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder-mountain.svg') }}';">
                        <div class="m-card__tags">
                            <span class="m-tag"><i class="fa fa-mountain"></i>{{ $gunung->nama_gunung }}</span>
                            <span class="m-tag m-tag--price"><i class="fa fa-tag"></i> Rp {{ number_format($r->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="m-card__over"><div class="alt" style="font-size:1.3rem;"><i class="fa fa-route me-2" style="font-size:1rem;"></i>{{ $r->nama_rute }}</div></div>
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

                        <div class="m-card__actions">
                            <a href="{{ route('pendaftaran.create.with.params', ['gunung_id' => $gunung->id_gunung, 'rute_id' => $r->id_rute]) }}" class="btn btn-simaksi-primary w-100 fw-bold">
                                <i class="fa fa-file-pen me-2"></i> Daftar SIMAKSI
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="card-simaksi p-5 text-center">
                    <div class="stat-tile__icon mx-auto"><i class="fa fa-route"></i></div>
                    <div class="fw-bold mb-1">Tidak ada data rute pendakian</div>
                    <div class="text-muted" style="font-size:.95rem;">Coba pilih gunung lainnya.</div>
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
    @if($gunung->latitude && $gunung->longitude)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
        <script>
            function initMap(){
                var lat = parseFloat('{{ $gunung->latitude }}');
                var lng = parseFloat('{{ $gunung->longitude }}');
                var center = { lat: lat, lng: lng };
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: center,
                    zoom: 12
                });
                new google.maps.Marker({ position: center, map: map, title: '{{ $gunung->nama_gunung }}' });
            }
        </script>
    @endif
@endpush
