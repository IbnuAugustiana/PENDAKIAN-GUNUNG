@extends('admin.layouts.app')

@section('title', 'Tambah Gunung - Admin')

@section('content')
<div class="container mt-5">
    <h2>🆕 Tambah Data Gunung</h2>
    <a href="{{ route('admin.gunung.index') }}" class="btn btn-secondary mb-3">⬅️ Kembali</a>

    <form action="{{ route('admin.gunung.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm" data-confirm-message="Simpan data gunung baru ini?" data-confirm-title="Konfirmasi Simpan" data-confirm-ok="Ya, simpan">
        @csrf

        <div class="mb-3">
            <label for="nama_gunung" class="form-label fw-bold">Nama Gunung</label>
            <input type="text" name="nama_gunung" id="nama_gunung" class="form-control" placeholder="Contoh: Gunung Semeru" value="{{ old('nama_gunung') }}" data-validate="name" minlength="3" maxlength="100" required>
            @error('nama_gunung') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label fw-bold">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Contoh: Lumajang, Jawa Timur" value="{{ old('lokasi') }}" data-validate="name" minlength="3" maxlength="120" required>
            @error('lokasi') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="latitude" class="form-label fw-bold">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Contoh: -8.1083" value="{{ old('latitude') }}">
            @error('latitude') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="longitude" class="form-label fw-bold">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Contoh: 112.9075" value="{{ old('longitude') }}">
            @error('longitude') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="ketinggian" class="form-label fw-bold">Ketinggian (mdpl)</label>
            <input type="number" name="ketinggian" id="ketinggian" class="form-control" placeholder="Contoh: 3676" value="{{ old('ketinggian') }}" min="1" data-validate="quantity" required>
            @error('ketinggian') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="harga_simaksi" class="form-label fw-bold">Harga SIMAKSI (Rp)</label>
            <input type="number" name="harga_simaksi" id="harga_simaksi" class="form-control" placeholder="Contoh: 15000" value="{{ old('harga_simaksi') }}" min="0" data-validate="price">
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" placeholder="Tuliskan deskripsi singkat gunung...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label fw-bold">Gambar Gunung</label>
            <input type="file" name="gambar" id="gambar" class="form-control" accept=".jpg,.jpeg,.png" data-validate="file" data-allowed-ext="jpg,jpeg,png">
        </div>

        <button type="submit" class="btn btn-success px-4">💾 Simpan</button>
    </form>
</div>
@endsection
