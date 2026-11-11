@extends('admin.layouts.app')

@section('title', 'Manajemen Pembayaran - Admin')

@section('content')

{{-- Breadcrumb + Page heading --}}
<div class="mb-4">
    <div class="breadcrumb-simaksi mb-2">
        <span class="crumb-trail">Admin / Pembayaran</span>
        <span class="crumb-title">Riwayat Pembayaran</span>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h1 class="h3 mb-1 fw-bold">💳 Riwayat Pembayaran</h1>
            <p class="mb-0 text-muted">Pantau transaksi pembayaran SIMAKSI.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<div class="card-simaksi">
    <div class="card-header">
        <div class="fw-bold"><i class="fas fa-receipt me-2" style="color:var(--admin-accent);"></i>Daftar Pembayaran</div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-muted">No</th>
                        <th class="text-muted">Gambar Gunung</th>
                        <th class="text-muted">Pendaftar</th>
                        <th class="text-muted">Gunung</th>
                        <th class="text-muted">Rute</th>
                        <th class="text-muted">Jumlah Bayar</th>
                        <th class="text-muted">Metode</th>
                        <th class="text-muted">Status</th>
                        <th class="text-muted">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pembayarans as $p)
                        <tr>
                            <td class="fw-semibold">{{ $loop->iteration }}</td>
                            <td>
                                @if($p->pendaftaran->gunung && $p->pendaftaran->gunung->gambar)
                                    <img src="{{ asset('images/gunung/' . $p->pendaftaran->gunung->gambar) }}" alt="{{ $p->pendaftaran->gunung->nama_gunung }}" width="80" height="60" class="rounded" style="object-fit:cover;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $p->pendaftaran->user->nama_lengkap ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $p->pendaftaran->gunung->nama_gunung ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $p->pendaftaran->rutePendakian->nama_rute ?? 'N/A' }}</td>
                            <td class="fw-semibold">Rp {{ number_format($p->pendaftaran->total_biaya ?? 0, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $p->metode_pembayaran ?? 'Midtrans' }}</td>
                            <td>
                                @if($p->status == 'success')
                                    <span class="badge bg-success rounded-pill px-3 py-2">Berhasil</span>
                                @elseif($p->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Pending</span>
                                @elseif($p->status == 'failed')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Gagal</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    <a href="{{ route('admin.pembayaran.edit', $p->id_pembayaran) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-pen me-1"></i>Edit
                                    </a>

                                    <form action="{{ route('admin.pembayaran.destroy', $p->id_pembayaran) }}" method="POST" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" data-confirm-delete>
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div>
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <div class="fw-bold">Belum ada data pembayaran</div>
                                    <div class="text-muted small mt-1">Saat transaksi tersedia, data akan tampil di sini.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const btns = document.querySelectorAll('[data-confirm-delete]');
    btns.forEach(btn => {
        btn.addEventListener('click', function(e){
            // SweetAlert2 is loaded in admin layout.
            if (typeof Swal !== 'undefined') {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            }
        });
    });
});
</script>
@endpush

@endsection
