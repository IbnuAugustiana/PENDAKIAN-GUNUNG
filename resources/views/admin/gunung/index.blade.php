@extends('admin.layouts.app')

@section('title', 'Manajemen Gunung - Admin')

@section('content')
<div class="admin-page">

    {{-- Breadcrumb + Page heading --}}
    <div class="mb-4">
        <div class="breadcrumb-simaksi mb-2">
            <span class="crumb-trail">Admin / Gunung</span>
            <span class="crumb-title">Manajemen Gunung</span>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h1 class="h3 mb-1 fw-bold">🗻 Manajemen Data Gunung</h1>
                <p class="mb-0 text-muted">Kelola data gunung pendakian.</p>
            </div>
            <a href="{{ route('admin.gunung.create') }}" class="btn btn-simaksi-primary text-white">
                <i class="fas fa-plus me-2"></i>Tambah Gunung
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

    {{-- Card Container --}}
    <div class="card-simaksi">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="fw-bold"><i class="fas fa-mountain me-2" style="color:var(--admin-accent);"></i>Daftar Gunung</div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="gunungTable">
                    <thead>
                        <tr>
                            <th class="text-muted">No</th>
                            <th class="text-muted">Gambar</th>
                            <th class="text-muted">Nama Gunung</th>
                            <th class="text-muted">Lokasi</th>
                            <th class="text-muted">Tinggi</th>
                            <th class="text-muted">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gunungs as $g)
                            <tr>
                                <td class="fw-semibold">{{ $loop->iteration }}</td>
                                <td>
                                    @if($g->gambar)
                                        <img src="{{ asset('images/gunung/' . $g->gambar) }}" alt="{{ $g->nama_gunung }}" width="80" height="60" class="rounded" style="object-fit:cover; border:1px solid var(--admin-border);" >
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $g->nama_gunung }}</td>
                                <td class="text-muted">{{ $g->lokasi }}</td>
                                <td>{{ $g->ketinggian }} mdpl</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.gunung.edit', $g->id_gunung) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-pen me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('admin.gunung.destroy', $g->id_gunung) }}" method="POST" data-confirm-message="Yakin ingin menghapus data gunung ini?" data-confirm-title="Konfirmasi Hapus" data-confirm-ok="Ya, hapus" data-confirm-icon="warning">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">
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
                                        <i class="fas fa-mountain-sun fa-3x text-muted mb-3"></i>
                                        <div class="fw-bold">Belum ada data gunung</div>
                                        <div class="text-muted small mt-1">Tambah gunung untuk memulai pendakian.</div>
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
