@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran - Admin')

@section('content')
<div class="container mt-5">
    <h2>📋 Detail Pembayaran</h2>
    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Data Pendaftar</h6>
                    <p><strong>Nama:</strong> {{ $pembayaran->pendaftaran->user->nama_lengkap ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $pembayaran->pendaftaran->user->email ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Data Pendakian</h6>
                    <p><strong>Gunung:</strong> {{ $pembayaran->pendaftaran->gunung->nama_gunung ?? 'N/A' }}</p>
                    <p><strong>Rute:</strong> {{ $pembayaran->pendaftaran->rutePendakian->nama_rute ?? 'N/A' }}</p>
                    <p><strong>Harga Rute:</strong> Rp {{ number_format($pembayaran->pendaftaran->rutePendakian->harga ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Detail Pembayaran</h6>
                    <p><strong>Metode Pembayaran:</strong> {{ $pembayaran->metode_pembayaran }}</p>
                    <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong>
                        @if($pembayaran->status == 'success')
                            <span class="badge bg-success">Berhasil</span>
                        @elseif($pembayaran->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($pembayaran->status == 'failed')
                            <span class="badge bg-danger">Gagal</span>
                        @else
                            <span class="badge bg-secondary">{{ $pembayaran->status }}</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Tambahan</h6>
                    @if($pembayaran->snap_token)
                        <p><strong>Snap Token:</strong> <code>{{ $pembayaran->snap_token }}</code></p>
                    @endif
                    <p><strong>Tanggal Dibuat:</strong> {{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Terakhir Diupdate:</strong> {{ $pembayaran->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.pembayaran.edit', $pembayaran->id_pembayaran) }}" class="btn btn-warning">✏️ Edit Pembayaran</a>
    </div>
</div>
@endsection
