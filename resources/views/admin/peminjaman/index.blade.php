@extends('admin.layouts.app')

@section('title', 'Verifikasi Peminjaman Alat - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-gradient-primary text-white rounded-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-1 fw-bold">🛠️ Status Peminjaman Alat</h1>
                            <p class="mb-0 opacity-75">Pantau status peminjaman alat pendakian dari Sistem Peminjaman Alat</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ rtrim((string) config('services.peminjaman_api.base_url'), '/') }}/admin/login" target="_blank" class="btn btn-light btn-lg rounded-pill shadow-sm">
                                <i class="fas fa-external-link-alt me-2"></i>Buka Sistem Peminjaman
                            </a>
                            <button class="btn btn-outline-light btn-lg rounded-pill shadow-sm" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-tools text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Peminjaman</h6>
                            <h4 class="mb-0 fw-bold">{{ $peminjamans->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Menunggu Verifikasi</h6>
                            <h4 class="mb-0 fw-bold">{{ $peminjamans->where('status', 'menunggu')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Disetujui</h6>
                            <h4 class="mb-0 fw-bold">{{ $peminjamans->where('status', 'disetujui')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-times-circle text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Ditolak</h6>
                        <h4 class="mb-0 fw-bold">{{ $peminjamans->where('status', 'ditolak')->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">📊 Daftar Peminjaman Alat</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dataTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-muted py-3 px-4">ID Peminjaman</th>
                                    <th class="border-0 fw-bold text-muted py-3">Peminjam</th>
                                    <th class="border-0 fw-bold text-muted py-3">ID Pendaftaran</th>
                                    <th class="border-0 fw-bold text-muted py-3">Alat yang Dipinjam</th>
                                    <th class="border-0 fw-bold text-muted py-3">Total Biaya</th>
                                    <th class="border-0 fw-bold text-muted py-3">Status</th>
                                    <th class="border-0 fw-bold text-muted py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamans as $peminjaman)
                                    <tr class="align-middle">
                                        <td class="py-3 px-4 fw-bold text-primary">#{{ $peminjaman->id }}</td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $peminjaman->user->nama_lengkap ?? $peminjaman->user->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $peminjaman->user->email ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($peminjaman->pendaftaran)
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                                    #{{ $peminjaman->pendaftaran->id_pendaftaran }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($peminjaman->peminjamanDetails as $detail)
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="fw-semibold">{{ $detail->alat->nama }}</span>
                                                        <span class="badge bg-secondary">{{ $detail->jumlah }} pcs</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                                <span class="fw-bold text-success">Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($peminjaman->status == 'disetujui')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Disetujui
                                                </span>
                                            @elseif($peminjaman->status == 'ditolak')
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i>Menunggu
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-info btn-sm rounded-pill px-3"
                                                        onclick="showDetailModal({{ $peminjaman->id }})">
                                                    <i class="fas fa-eye me-1"></i>Lihat
                                                </button>
                                                <a href="{{ rtrim((string) config('services.peminjaman_api.base_url'), '/') }}/admin/peminjaman/{{ $peminjaman->id }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                                    <i class="fas fa-external-link-alt me-1"></i>Verifikasi
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Data Peminjaman</h5>
                                                <p class="text-muted mb-3">Belum ada permintaan peminjaman alat</p>
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
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.avatar {
    width: 40px;
    height: 40px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }

    .table-responsive {
        border-radius: 0.375rem;
    }

    .card-body {
        padding: 1rem;
    }

    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .btn {
        width: 100%;
    }
}
</style>

<script>
function showDetailModal(peminjamanId) {
    // Load detail via AJAX
    fetch(`/admin/peminjaman/${peminjamanId}`)
        .then(response => response.json())
        .then(data => {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary">Informasi Peminjam</h6>
                        <p><strong>Nama:</strong> ${data.peminjaman.user.nama_lengkap || data.peminjaman.user.name || '-'}</p>
                        <p><strong>Email:</strong> ${data.peminjaman.user.email || '-'}</p>
                        <p><strong>ID Pendaftaran:</strong> ${data.peminjaman.pendaftaran ? '#' + data.peminjaman.pendaftaran.id_pendaftaran : '-'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary">Status Peminjaman</h6>
                        <p><strong>Status:</strong> <span class="badge bg-${data.peminjaman.status === 'disetujui' ? 'success' : data.peminjaman.status === 'ditolak' ? 'danger' : 'warning'}">${data.peminjaman.status}</span></p>
                        <p><strong>Total Biaya:</strong> Rp ${data.peminjaman.total_biaya.toLocaleString()}</p>
                        <p><strong>Tanggal Dibuat:</strong> ${new Date(data.peminjaman.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
                <hr>
                <h6 class="fw-bold text-primary">Detail Alat yang Dipinjam</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Alat</th>
                                <th>Jumlah</th>
                                <th>Harga per Unit</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>`;

            data.peminjaman.peminjaman_details.forEach(detail => {
                html += `
                    <tr>
                        <td>${detail.alat.nama}</td>
                        <td>${detail.jumlah}</td>
                        <td>Rp ${detail.harga.toLocaleString()}</td>
                        <td>Rp ${(detail.jumlah * detail.harga).toLocaleString()}</td>
                    </tr>`;
            });

            html += `
                        </tbody>
                    </table>
                </div>`;

            document.getElementById('detailModalBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => {
            console.error('Error loading detail:', error);
            alert('Gagal memuat detail peminjaman');
        });
}



// Initialize DataTable if available
$(document).ready(function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#dataTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "searching": true,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    }
});
</script>
@endsection
