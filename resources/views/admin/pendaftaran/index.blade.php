@extends('admin.layouts.app')

@section('title', 'Manajemen Pendaftaran - Admin')

@section('content')
<div class="admin-page">

    {{-- Breadcrumb + Page heading (tanpa ubah route/controller) --}}
    <div class="mb-4">
        <div class="breadcrumb-simaksi mb-2">
            <span class="crumb-trail">Admin / Pendaftaran</span>
            <span class="crumb-title">Manajemen Pendaftaran</span>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h1 class="h3 mb-1 fw-bold">📋 Manajemen Pendaftaran</h1>
                <p class="mb-0 text-muted">Kelola dan setujui pendaftaran pendakian</p>
            </div>
            <div>
                <button class="btn btn-outline-primary" type="button" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
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
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-clipboard-list text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Pendaftaran</h6>
                            <h4 class="mb-0 fw-bold">{{ $pendaftarans->count() }}</h4>
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
                            <h6 class="text-muted mb-1">Menunggu Persetujuan</h6>
                            <h4 class="mb-0 fw-bold">{{ $pendaftarans->where('status', 'menunggu')->count() }}</h4>
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
                            <h4 class="mb-0 fw-bold">{{ $pendaftarans->where('status', 'disetujui')->count() }}</h4>
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
                            <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-times-circle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Ditolak</h6>
                            <h4 class="mb-0 fw-bold">{{ $pendaftarans->where('status', 'ditolak')->count() }}</h4>
                        </div>
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
                    <h5 class="mb-0 fw-bold text-dark">📊 Daftar Pendaftaran</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="dataTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 fw-bold text-muted py-3 px-4">ID</th>
                                    <th class="border-0 fw-bold text-muted py-3">Gambar Gunung</th>
                                    <th class="border-0 fw-bold text-muted py-3">Pendaftar</th>
                                    <th class="border-0 fw-bold text-muted py-3">Gunung</th>
                                    <th class="border-0 fw-bold text-muted py-3">Rute</th>
                                    <th class="border-0 fw-bold text-muted py-3">Tanggal Pendakian</th>
                                    <th class="border-0 fw-bold text-muted py-3">Status</th>
                                    <th class="border-0 fw-bold text-muted py-3">Peminjaman ID</th>
                                    <th class="border-0 fw-bold text-muted py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendaftarans as $pendaftaran)
                                    <tr class="align-middle">
                                        <td class="py-3 px-4 fw-bold text-primary">#{{ $pendaftaran->id_pendaftaran }}</td>
                                        <td class="py-3">
                                            @if($pendaftaran->gunung && $pendaftaran->gunung->gambar)
                                                <img src="{{ asset('images/gunung/' . $pendaftaran->gunung->gambar) }}"
                                                     alt="{{ $pendaftaran->gunung->nama_gunung }}"
                                                     class="rounded shadow-sm"
                                                     style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 80px; height: 60px;">
                                                    <i class="fas fa-mountain text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $pendaftaran->user->nama_lengkap ?? '-' }}</div>
                                                    <small class="text-muted">{{ $pendaftaran->user->email ?? '-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mountain text-success me-2"></i>
                                                <span class="fw-semibold">{{ $pendaftaran->gunung->nama_gunung ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-route text-info me-2"></i>
                                                <span class="fw-semibold">{{ $pendaftaran->rutePendakian->nama_rute ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-warning me-2"></i>
                                                <span>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendakian)->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @if($pendaftaran->status == 'disetujui')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Disetujui
                                                </span>
                                            @elseif($pendaftaran->status == 'ditolak')
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
                                            @if($pendaftaran->peminjaman_id)
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                                    <i class="fas fa-tools me-1"></i>{{ $pendaftaran->peminjaman_id }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.pendaftaran.show', $pendaftaran->id_pendaftaran) }}"
                                                   class="btn btn-info btn-sm rounded-pill px-3">
                                                    <i class="fas fa-eye me-1"></i>Lihat
                                                </a>
                                                <form method="POST" action="{{ route('admin.pendaftaran.update-status', $pendaftaran) }}"
                                                      class="d-inline" id="statusForm{{ $pendaftaran->id_pendaftaran }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                            class="form-select form-select-sm d-inline-block"
                                                            data-current-status="{{ $pendaftaran->status }}"
                                                            style="width: auto; min-width: 120px;"
                                                            onchange="confirmStatusChange(this, {{ $pendaftaran->id_pendaftaran }})">
                                                        <option value="menunggu" {{ $pendaftaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                        <option value="disetujui" {{ $pendaftaran->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                        <option value="ditolak" {{ $pendaftaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum Ada Data Pendaftaran</h5>
                                                <p class="text-muted mb-3">Belum ada pendaftaran yang masuk</p>
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

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.form-select {
    border-radius: 0.375rem;
    border: 1px solid #dee2e6;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
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
function confirmStatusChange(selectElement, id) {
    const newStatus = selectElement.value;
    const currentStatus = selectElement.dataset.currentStatus || selectElement.querySelector('option[selected]')?.value;

    if (newStatus === currentStatus) {
        return; // No change
    }

    let statusText = '';
    let confirmMessage = '';

    switch(newStatus) {
        case 'disetujui':
            statusText = 'Disetujui';
            confirmMessage = 'Apakah Anda yakin ingin MENYETUJUI pendaftaran ini? User akan dapat mencetak formulir SIMAKSI.';
            break;
        case 'ditolak':
            statusText = 'Ditolak';
            confirmMessage = 'Apakah Anda yakin ingin MENOLAK pendaftaran ini?';
            break;
        case 'menunggu':
            statusText = 'Menunggu';
            confirmMessage = 'Apakah Anda yakin ingin mengubah status menjadi MENUNGGU?';
            break;
    }

    Swal.fire({
        icon: newStatus === 'ditolak' ? 'warning' : 'question',
        title: `Ubah status menjadi ${statusText}?`,
        text: confirmMessage,
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            selectElement.dataset.currentStatus = newStatus;
            selectElement.form.submit();
        } else {
            selectElement.value = currentStatus;
        }
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
