@extends('layouts.main')

@section('title', 'Detail Pendaftaran SIMAKSI')

@section('content')

<div class="app-content">

    <div class="page-heading">
        <div class="breadcrumb-simaksi">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="mx-2">/</span>
            <span>SIMAKSI</span>
            <span class="mx-2">/</span>
            <span class="fw-semibold">Detail Pendaftaran</span>
        </div>
        <h1 class="fw-bold">Detail Pendaftaran</h1>
        <p class="mb-0">Tinjau data pendaftaran dan pantau status yang diproses oleh admin.</p>
    </div>

    @php
        $gunung = $pendaftaran->rutePendakian->gunung ?? null;
        $rute = $pendaftaran->rutePendakian ?? null;
        $user = $pendaftaran->user ?? null;
        $statusValue = strtolower((string) ($pendaftaran->status ?? 'pending'));
        $paymentValue = strtolower((string) ($pendaftaran->status_pembayaran ?? 'belum'));
        $statusLabel = match (true) {
            in_array($statusValue, ['approved', 'disetujui']) => 'Disetujui',
            in_array($statusValue, ['rejected', 'ditolak', 'dibatalkan', 'cancelled']) => 'Dibatalkan',
            $statusValue === 'selesai' => 'Selesai',
            default => 'Menunggu',
        };
        $paymentLabel = $paymentValue === 'lunas' ? 'Lunas' : 'Belum Bayar';
    @endphp

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card-simaksi">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <i class="fa fa-circle-info me-2" style="color:var(--simaksi-primary);"></i>
                            Detail Pendaftaran
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-success border border-success border-opacity-25">
                            <i class="fa fa-lock me-1"></i>READ ONLY
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fa fa-mountain me-2" style="color:var(--simaksi-primary);"></i>Informasi Gunung</h5>

                        <div class="mb-3">
                            <label class="form-label">Gunung</label>
                            <input type="text" class="form-control" value="{{ $gunung->nama_gunung ?? '-' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rute</label>
                            <input type="text" class="form-control" value="{{ $rute->nama_rute ?? '-' }}" readonly>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Naik</label>
                                <input type="date" class="form-control" value="{{ $pendaftaran->tanggal_pendakian }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Turun</label>
                                <input type="date" class="form-control" value="{{ $pendaftaran->tanggal_turun }}" readonly>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Jumlah Pendaki</label>
                            <input type="number" class="form-control" value="{{ $pendaftaran->jumlah_pendaki }}" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fa fa-user me-2" style="color:var(--simaksi-primary);"></i>Informasi Pendaki</h5>

                        <div class="mb-3">
                            <label class="form-label">User</label>
                            <input type="text" class="form-control" value="{{ $user->nama_lengkap ?? $user->name ?? '-' }}" readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fa fa-credit-card me-2" style="color:var(--simaksi-primary);"></i>Informasi Pembayaran</h5>

                        <div class="mb-3">
                            <label class="form-label">Status Pendaftaran</label>
                            <div class="form-control d-flex align-items-center" style="background:#f8fbf9; min-height:52px;">
                                @if(in_array($statusValue, ['approved', 'disetujui']))
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="fa fa-check me-1"></i>{{ $statusLabel }}
                                    </span>
                                @elseif(in_array($statusValue, ['rejected', 'ditolak', 'dibatalkan', 'cancelled']))
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <i class="fa fa-xmark me-1"></i>{{ $statusLabel }}
                                    </span>
                                @elseif($statusValue === 'selesai')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        <i class="fa fa-flag-checkered me-1"></i>{{ $statusLabel }}
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                        <i class="fa fa-clock me-1"></i>{{ $statusLabel }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-text">Status pendaftaran hanya dapat diubah oleh admin.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <div class="form-control d-flex align-items-center" style="background:#f8fbf9; min-height:52px;">
                                @if($paymentValue === 'lunas')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                        <i class="fa fa-wallet me-1"></i>{{ $paymentLabel }}
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        <i class="fa fa-triangle-exclamation me-1"></i>{{ $paymentLabel }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-text">Status pembayaran diproses oleh sistem dan admin.</div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-primary fw-bold">
                            <i class="fa fa-arrow-left me-2"></i>Kembali
                        </a>
                        @if(in_array($statusValue, ['approved', 'disetujui']) && $paymentValue === 'lunas')
                            <a href="{{ route('pendaftaran.cetak', $pendaftaran->id_pendaftaran) }}" class="btn btn-simaksi-primary text-white fw-bold">
                                <i class="fa fa-file-pdf me-2"></i>Cetak PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card-simaksi">
                <div class="card-header">
                    <div class="fw-bold"><i class="fa fa-bolt me-2" style="color:var(--simaksi-primary);"></i>Status</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small mb-1">Status Pendaftaran</div>
                        <div>
                            @if(in_array($statusValue, ['approved', 'disetujui']))
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="fa fa-check me-1"></i>Disetujui</span>
                            @elseif(in_array($statusValue, ['rejected', 'ditolak', 'dibatalkan', 'cancelled']))
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="fa fa-xmark me-1"></i>Dibatalkan</span>
                            @elseif($statusValue === 'selesai')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><i class="fa fa-flag-checkered me-1"></i>Selesai</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25"><i class="fa fa-clock me-1"></i>Menunggu</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small mb-1">Status Pembayaran</div>
                        <div>
                            @if($paymentValue === 'lunas')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><i class="fa fa-wallet me-1"></i>Lunas</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25"><i class="fa fa-triangle-exclamation me-1"></i>Belum Bayar</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="fw-bold mb-3"><i class="fa fa-diagram-project me-2" style="color:var(--simaksi-primary);"></i>Timeline Status</div>
                        <div class="timeline">
                            <div class="timeline-item {{ in_array($statusValue, ['pending', 'menunggu']) ? 'is-current' : (in_array($statusValue, ['approved', 'disetujui', 'rejected', 'ditolak', 'dibatalkan', 'selesai']) ? 'is-done' : '') }}">
                                <div class="timeline-marker"><i class="fa fa-clock"></i></div>
                                <div class="timeline-title">Pengajuan</div>
                                <div class="timeline-meta">Menunggu proses</div>
                            </div>
                            <div class="timeline-item {{ in_array($statusValue, ['approved', 'disetujui', 'selesai']) ? 'is-approved' : (in_array($statusValue, ['rejected', 'ditolak', 'dibatalkan', 'cancelled']) ? 'is-rejected' : '') }}">
                                <div class="timeline-marker"><i class="fa {{ in_array($statusValue, ['approved', 'disetujui', 'selesai']) ? 'fa-check' : 'fa-xmark' }}"></i></div>
                                <div class="timeline-title">Keputusan Admin</div>
                                <div class="timeline-meta">{{ $statusLabel }}</div>
                            </div>
                            <div class="timeline-item {{ $paymentValue === 'lunas' ? 'is-approved' : '' }}">
                                <div class="timeline-marker"><i class="fa fa-credit-card"></i></div>
                                <div class="timeline-title">Pembayaran</div>
                                <div class="timeline-meta">{{ $paymentLabel }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
