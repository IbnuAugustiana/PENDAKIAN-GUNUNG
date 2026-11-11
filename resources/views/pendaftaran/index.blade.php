@extends('layouts.main')

@section('title', 'Daftar Pendaftaran SIMAKSI')

@section('content')

<div class="app-content">

    {{-- ===================== Page Heading + Breadcrumb ===================== --}}
    <div class="page-heading">
        <div class="breadcrumb-simaksi">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="mx-2">/</span>
            <span>SIMAKSI</span>
            <span class="mx-2">/</span>
            <span class="fw-semibold">Pendaftaran</span>
        </div>
        <h1 class="fw-bold">📋 Pendaftaran Anda</h1>
        <p>Kelola dan pantau status pendaftaran pendakian Anda.</p>
    </div>

    {{-- ===================== Alert ===================== --}}
    @if(session('success') && str_contains(session('success'), 'pendaftaran'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- ===================== Summary Cards ===================== --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card-simaksi p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:var(--simaksi-primary-soft);color:var(--simaksi-primary);">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:.95rem;">Total Pendaftaran</div>
                        <div class="fw-bold" style="font-size:1.6rem; line-height:1;">{{ $pendaftarans->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card-simaksi p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#fff7ed;color:#b45309;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:.95rem;">Menunggu Persetujuan</div>
                        <div class="fw-bold" style="font-size:1.6rem; line-height:1;">{{ $pendaftarans->where('status', 'pending')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card-simaksi p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#dcfce7;color:#15803d;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:.95rem;">Disetujui</div>
                        <div class="fw-bold" style="font-size:1.6rem; line-height:1;">{{ $pendaftarans->where('status', 'disetujui')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card-simaksi p-4 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:#fee2e2;color:#b91c1c;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:.95rem;">Belum Bayar</div>
                        <div class="fw-bold" style="font-size:1.6rem; line-height:1;">{{ $pendaftarans->where('status_pembayaran', 'belum')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-simaksi">
        {{-- ===================== Table Header ===================== --}}
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-table text-success" style="font-size:1rem;"></i>
                <h5 class="mb-0 fw-bold">Detail Pendaftaran</h5>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pendaftaran.create') }}" class="btn btn-simaksi-primary text-white">
                    <i class="fas fa-plus me-2"></i> Tambah
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="text-muted">#</th>
                            <th class="text-muted">Pendaftar</th>
                            <th class="text-muted">Gunung</th>
                            <th class="text-muted">Rute</th>
                            <th class="text-muted">Tanggal</th>
                            <th class="text-muted">Jumlah</th>
                            <th class="text-muted">Status</th>
                            <th class="text-muted">Pembayaran</th>
                            <th class="text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $p)
                            <tr>
                                <td class="fw-bold text-success">#{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:var(--simaksi-primary-soft);color:var(--simaksi-primary);">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $p->user->nama_lengkap ?? $p->user->name ?? '-' }}</div>
                                            @if($p->tanggal_turun)
                                                <div class="small text-muted">Turun: {{ \Carbon\Carbon::parse($p->tanggal_turun)->format('d M Y') }}</div>
                                            @else
                                                <div class="small text-muted">Turun: -</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-mountain" style="color:var(--simaksi-primary);"></i>
                                        <span class="fw-semibold">{{ $p->rutePendakian->gunung->nama_gunung ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold text-muted">{{ $p->rutePendakian->nama_rute ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal_pendakian)->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $p->jumlah_pendaki ?? 1 }} orang</div>
                                </td>
                                <td>
                                    @if($p->status == 'disetujui')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Disetujui</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Menunggu</span>
                                    @endif
                                    @if($p->peminjaman_id)
                                        <div class="small text-muted mt-1"><i class="fas fa-toolbox me-1"></i>{{ $p->peminjaman_id }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status_pembayaran == 'lunas')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Lunas</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Belum</span>
                                        <div class="small text-muted mt-1">Bayar saat pendaftaran</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($p->status == 'disetujui')
                                            <a href="{{ route('pendaftaran.cetak', $p->id_pendaftaran) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download me-1"></i> Cetak PDF
                                            </a>
                                        @else
                                            <a href="{{ route('pendaftaran.edit', $p->id_pendaftaran) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-center">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <div class="fw-bold">Belum ada data pendaftaran.</div>
                                        <div class="text-muted small mt-1">Klik tombol Tambah untuk memulai.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

