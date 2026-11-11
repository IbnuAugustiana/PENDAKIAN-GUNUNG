@extends('admin.layouts.app')

@section('title', 'Manajemen Rute Pendakian - Admin')

@section('content')

{{-- Breadcrumb + Page heading --}}
<div class="mb-4">
    <div class="breadcrumb-simaksi mb-2">
        <span class="crumb-trail">Admin / Rute Pendakian</span>
        <span class="crumb-title">Manajemen Rute Pendakian</span>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold">🗺️ Manajemen Rute Pendakian</h1>
            <p class="mb-0 text-muted">Kelola daftar rute pendakian.</p>
        </div>

        <a href="{{ route('admin.rute-pendakian.create') }}" class="btn btn-simaksi-primary text-white">
            <i class="fas fa-plus me-2"></i>Tambah Rute Pendakian
        </a>
    </div>
</div>

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

{{-- Table Card --}}
<div class="card-simaksi">
    <div class="card-header">
        <div class="fw-bold"><i class="fas fa-route me-2" style="color:var(--admin-accent);"></i>Daftar Rute</div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-muted">No</th>
                        <th class="text-muted">Gambar Gunung</th>
                        <th class="text-muted">Nama Rute</th>
                        <th class="text-muted">Gunung</th>
                        <th class="text-muted">Harga (Rp)</th>
                        <th class="text-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rute_pendakians as $rute)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                @if($rute->gunung && $rute->gunung->gambar)
                                    <img src="{{ asset('images/gunung/' . $rute->gunung->gambar) }}" alt="{{ $rute->gunung->nama_gunung }}" width="80" height="60" class="rounded" style="object-fit:cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $rute->nama_rute }}</td>
                            <td class="text-muted">{{ $rute->gunung->nama_gunung ?? 'N/A' }}</td>
                            <td class="fw-semibold">Rp {{ number_format($rute->harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.rute-pendakian.edit', $rute->id_rute) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-pen me-1"></i>Edit
                                    </a>

                                    <form action="{{ route('admin.rute-pendakian.destroy', $rute->id_rute) }}" method="POST" class="m-0" data-confirm-message="Yakin ingin menghapus data rute ini?" data-confirm-title="Konfirmasi Hapus" data-confirm-ok="Ya, hapus" data-confirm-icon="warning">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div>
                                    <i class="fas fa-compass fa-3x text-muted mb-3"></i>
                                    <div class="fw-bold">Belum ada data rute pendakian</div>
                                    <div class="text-muted small mt-1">Tambah rute pendakian untuk memulai pendakian.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
