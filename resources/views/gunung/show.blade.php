@extends('layouts.main')

@section('title', $gunung->nama_gunung)

@section('content')
<section class="container">
    {{-- Hero --}}
    <x-ui.page-header eyebrow="Detail Gunung" icon="fa-mountain" :title="$gunung->nama_gunung"
        subtitle="Informasi resmi untuk rute dan pendakian yang lebih terarah"
        :image="$gunung->gambar ? asset('images/gunung/' . $gunung->gambar) : null"
        :crumbs="['Gunung' => route('gunung.index'), $gunung->nama_gunung => null]">
        <span class="chip"><i class="fa fa-map-marker-alt"></i> {{ $gunung->lokasi }}</span>
        <span class="chip"><i class="fa fa-mountain"></i> {{ number_format($gunung->ketinggian,0,',','.') }} mdpl</span>
    </x-ui.page-header>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            {{-- Gallery/Media --}}
            <div class="card-simaksi overflow-hidden mb-4" data-reveal="fade-up">
                @if($gunung->gambar)
                    <div class="skeleton" style="height:340px;">
                        <img src="{{ asset('images/gunung/' . $gunung->gambar) }}" alt="{{ $gunung->nama_gunung }}"
                             class="w-100 h-100" style="object-fit:cover;"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder-mountain.svg') }}';">
                    </div>
                @else
                    <div class="p-5 text-center">
                        <div class="text-muted">Tidak ada gambar untuk gunung ini.</div>
                    </div>
                @endif
            </div>

            {{-- Informasi --}}
            <div class="card-simaksi p-4 p-md-5 mb-4" data-reveal="fade-up">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-info-circle"></i></span>
                    <h2 class="h4 fw-bold mb-0">Informasi</h2>
                </div>

                <div class="row g-3" data-stagger>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4" style="background:var(--c-sand-50); border:1px solid var(--c-line);">
                            <div class="text-muted fw-semibold mb-1" style="font-size:.85rem;">Ketinggian</div>
                            <div class="fw-bold" style="font-family:var(--font-display); font-size:1.5rem;"><span data-count="{{ (int) $gunung->ketinggian }}">{{ number_format($gunung->ketinggian,0,',','.') }}</span> <small class="text-muted" style="font-size:.85rem;">mdpl</small></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4" style="background:var(--c-sand-50); border:1px solid var(--c-line);">
                            <div class="text-muted fw-semibold mb-1" style="font-size:.85rem;">Harga SIMAKSI</div>
                            <div class="fw-bold" style="font-family:var(--font-display); font-size:1.5rem;">Rp {{ number_format($gunung->harga_simaksi ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-muted" style="line-height:1.85;">
                    {{ $gunung->deskripsi }}
                </div>
            </div>

            {{-- Fasilitas --}}
            <div class="card-simaksi p-4 p-md-5 mb-4" data-reveal="fade-up">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-cogs"></i></span>
                    <h2 class="h4 fw-bold mb-0">Fasilitas</h2>
                </div>
                <div class="row g-3" data-stagger="fade-left">
                    <div class="col-12 col-md-6">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="fa fa-check-circle text-success mt-1"></i>
                            <div>
                                <div class="fw-bold">Panduan Pendakian</div>
                                <div class="text-muted" style="line-height:1.7;">Akses panduan keselamatan sebelum memulai.</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="fa fa-check-circle text-success mt-1"></i>
                            <div>
                                <div class="fw-bold">Informasi Lokasi</div>
                                <div class="text-muted" style="line-height:1.7;">Tersedia peta lokasi gunung (jika koordinat tersedia).</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Peta (tetap business logic/JS) --}}
            @if($gunung->latitude && $gunung->longitude)
                <div class="card-simaksi p-4 p-md-5" data-reveal="fade-up">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-map"></i></span>
                        <h2 class="h4 fw-bold mb-0">Lokasi di Peta</h2>
                    </div>
                    <div id="map" class="w-100" style="height:420px; border-radius:16px; border:1px solid var(--c-line);"></div>
                </div>
            @endif
        </div>

        <div class="col-12 col-lg-4">
            <div class="card-simaksi p-4 sticky-top" style="top: calc(var(--nav-h) + 16px); z-index:5;" data-reveal="fade-left">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-briefcase"></i></span>
                    <h2 class="h4 fw-bold mb-0">Ringkasan</h2>
                </div>

                <div class="d-grid gap-3">
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <span class="text-muted fw-semibold">Gunung</span>
                        <span class="fw-bold text-end">{{ $gunung->nama_gunung }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <span class="text-muted fw-semibold">Lokasi</span>
                        <span class="fw-bold text-end">{{ $gunung->lokasi }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <span class="text-muted fw-semibold">Harga SIMAKSI</span>
                        <span class="fw-bold">Rp {{ number_format($gunung->harga_simaksi ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-grid gap-2">
                    <a href="{{ route('rute.by-gunung', $gunung->id_gunung) }}" class="btn btn-simaksi-primary w-100 fw-bold py-2">
                        <i class="fa fa-paper-plane me-2"></i> Lihat Rute & Daftar
                    </a>

                    <a href="{{ route('panduan') }}" class="btn btn-simaksi-secondary w-100 fw-bold py-2">
                        <i class="fa fa-book me-2"></i> Baca Panduan
                    </a>
                </div>
            </div>
        </div>
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
