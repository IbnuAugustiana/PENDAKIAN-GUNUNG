@extends('admin.layouts.app')

@section('title', 'Edit Pembayaran - Admin')

@section('content')
<div class="container mt-5">
    <h2>✏️ Edit Pembayaran</h2>
    <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <form action="{{ route('admin.pembayaran.update', $pembayaran->id_pembayaran) }}" method="POST" class="card p-4 shadow-sm" data-confirm-message="Simpan perubahan data pembayaran ini?" data-confirm-title="Konfirmasi Perubahan" data-confirm-ok="Ya, simpan">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_pendaftaran" class="form-label fw-bold">Pendaftaran</label>
            <select name="id_pendaftaran" id="id_pendaftaran" class="form-control" required>
                <option value="">Pilih Pendaftaran</option>
                @foreach($pendaftarans as $pendaftaran)
                    <option value="{{ $pendaftaran->id_pendaftaran }}" {{ old('id_pendaftaran', $pembayaran->id_pendaftaran) == $pendaftaran->id_pendaftaran ? 'selected' : '' }}>
                        {{ $pendaftaran->user->nama_lengkap ?? 'N/A' }} - {{ $pendaftaran->gunung->nama_gunung ?? 'N/A' }} ({{ $pendaftaran->rutePendakian->nama_rute ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
            @error('id_pendaftaran') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label fw-bold">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                <option value="">Pilih Metode</option>
                <option value="Midtrans" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Midtrans' ? 'selected' : '' }}>Midtrans</option>
                <option value="Transfer Bank" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="Cash" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Cash' ? 'selected' : '' }}>Cash</option>
            </select>
            @error('metode_pembayaran') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="jumlah_bayar" class="form-label fw-bold">Jumlah Bayar (Rp)</label>
            <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" min="0" data-validate="price" required>
            @error('jumlah_bayar') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label fw-bold">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">Pilih Status</option>
                <option value="pending" {{ old('status', $pembayaran->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="success" {{ old('status', $pembayaran->status) == 'success' ? 'selected' : '' }}>Berhasil</option>
                <option value="failed" {{ old('status', $pembayaran->status) == 'failed' ? 'selected' : '' }}>Gagal</option>
                <option value="cancelled" {{ old('status', $pembayaran->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="snap_token" class="form-label fw-bold">Snap Token (Midtrans)</label>
            <input type="text" name="snap_token" id="snap_token" class="form-control" value="{{ old('snap_token', $pembayaran->snap_token) }}">
            <small class="text-muted">Opsional - untuk pembayaran Midtrans</small>
            @error('snap_token') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary px-4">💾 Perbarui</button>
    </form>
</div>
@endsection
