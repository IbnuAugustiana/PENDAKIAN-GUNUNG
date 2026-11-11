@extends('layouts.main')

@section('title', 'Daftar Gunung')

@section('content')
<section class="container">
    <x-ui.page-header eyebrow="Destinasi" icon="fa-mountain" title="Daftar Gunung di Indonesia"
        subtitle="Temukan informasi lengkap tentang gunung dan rute pendakian di Indonesia."
        :image="asset('images/gunung/GunungLawu.jpg')" :crumbs="['Gunung' => null]">
        <span class="chip"><i class="fa fa-mountain"></i> {{ $gunungs->count() }} gunung</span>
        <span class="chip"><i class="fa fa-route"></i> Rute resmi</span>
    </x-ui.page-header>

    <div class="row g-3 align-items-stretch mb-4">
        <div class="{{ isset($query) ? 'col-12 col-lg-8' : 'col-12' }}" data-reveal="fade-up">
            <div class="card-simaksi p-3 p-md-4 h-100">
                <form class="row g-2 align-items-end" method="GET" action="{{ url()->current() }}" data-search-form role="search">
                    <div class="col-12 col-md-8">
                        <label class="form-label mb-1" for="gunungSearch"><i class="fa fa-magnifying-glass me-1 text-green"></i> Cari gunung</label>
                        <input id="gunungSearch" type="text" name="q" class="form-control" value="{{ request()->get('q') }}" placeholder="Masukkan nama gunung..." data-search-input maxlength="100">
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="submit" class="btn btn-simaksi-primary w-100 fw-bold" style="min-height:46px;">
                            <i class="fa fa-search me-2"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($query))
            <div class="col-12 col-lg-4" data-reveal="fade-left">
                <div class="card-simaksi p-3 p-md-4 h-100 d-flex align-items-center gap-3">
                    <div class="stat-tile__icon mb-0"><i class="fa fa-filter"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:.85rem;">Hasil pencarian</div>
                        <div class="fw-bold">"{{ $query }}"</div>
                        <div class="text-muted" style="font-size:.85rem;">{{ $gunungs->count() }} hasil</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($gunungs->isEmpty())
        <div class="card-simaksi p-5 text-center" data-reveal="scale">
            <div class="stat-tile__icon mx-auto"><i class="fa fa-mountain-sun"></i></div>
            <div class="fw-bold mb-1" style="font-family:var(--font-display); font-size:1.2rem;">Belum ada data gunung</div>
            <div class="text-muted" style="font-size:.95rem;">Coba ubah kata kunci pencarian.</div>
        </div>
    @else
        <div class="row g-3 g-lg-4" data-stagger>
            @foreach($gunungs as $gunung)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-ui.mountain-card
                        :href="route('rute.by-gunung', $gunung->id_gunung)"
                        :image="asset('images/gunung/' . ($gunung->gambar ?? 'default.jpg'))"
                        :name="$gunung->nama_gunung"
                        :location="$gunung->lokasi"
                        :height="$gunung->ketinggian"
                        :price="$gunung->harga_simaksi"
                        :description="$gunung->deskripsi"
                        :excerpt="Str::limit($gunung->deskripsi, 110)">
                        <x-slot:actions>
                            <a href="{{ route('rute.by-gunung', $gunung->id_gunung) }}" class="btn btn-simaksi-primary btn-sm fw-bold">
                                Lihat Rute <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                            @if($gunung->latitude && $gunung->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $gunung->latitude }},{{ $gunung->longitude }}" target="_blank" rel="noopener" class="btn btn-simaksi-secondary btn-sm fw-bold">
                                    <i class="fa fa-map me-1"></i> Maps
                                </a>
                            @endif
                        </x-slot:actions>
                    </x-ui.mountain-card>
                </div>
            @endforeach
        </div>

        {{-- Pagination (links hanya jika data paginate). Untuk koleksi biasa, hindari error links(). --}}
        <div class="mt-4">
            @if(method_exists($gunungs, 'links'))
                {{ $gunungs->links() }}
            @endif
        </div>
    @endif
</section>
@endsection
