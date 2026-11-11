@extends('admin.layouts.app')

@section('title', 'Edit Rute Pendakian - Admin')

@section('content')
<div class="container mt-5">
    <h2>✏️ Edit Rute Pendakian</h2>
    <a href="{{ route('admin.rute-pendakian.index') }}" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <form action="{{ route('admin.rute-pendakian.update', $rute_pendakian->id_rute) }}" method="POST" class="card p-4 shadow-sm" data-confirm-message="Simpan perubahan data rute ini?" data-confirm-title="Konfirmasi Perubahan" data-confirm-ok="Ya, simpan">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="gunung_id" class="form-label fw-bold">Gunung</label>
            <select name="gunung_id" id="gunung_id" class="form-control" required>
                <option value="">Pilih Gunung</option>
                @foreach($gunungs as $gunung)
                    <option value="{{ $gunung->id_gunung }}" {{ old('gunung_id', $rute_pendakian->gunung_id) == $gunung->id_gunung ? 'selected' : '' }}>
                        {{ $gunung->nama_gunung }}
                    </option>
                @endforeach
            </select>
            @error('gunung_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="nama_rute" class="form-label fw-bold">Nama Rute</label>
            <input type="text" name="nama_rute" id="nama_rute" class="form-control" value="{{ old('nama_rute', $rute_pendakian->nama_rute) }}" data-validate="name" minlength="3" maxlength="100" required>
            @error('nama_rute') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $rute_pendakian->deskripsi) }}</textarea>
            @error('deskripsi') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label fw-bold">Harga (Rp)</label>
            <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $rute_pendakian->harga) }}" min="0" data-validate="price" required>
            @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary px-4">💾 Perbarui</button>
    </form>
</div>
@endsection
