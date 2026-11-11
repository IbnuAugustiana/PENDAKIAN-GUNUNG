@extends('layouts.main')

@section('content')

<div class="container">
    <h1>Edit Anggota Pendakian</h1>

```
@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('anggota_pendakian.update', $anggota->id_anggota) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Pendaftaran</label>
        <select name="id_pendaftaran" class="form-control" required>
            @foreach($pendaftarans as $p)
                <option value="{{ $p->id_pendaftaran }}" {{ $anggota->id_pendaftaran == $p->id_pendaftaran ? 'selected' : '' }}>
                    #{{ $p->id_pendaftaran }} - {{ $p->user->nama_lengkap ?? 'User' }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Nama Anggota</label>
        <input type="text" name="nama_anggota" class="form-control" value="{{ $anggota->nama_anggota }}" required>
    </div>

    <div class="mb-3">
        <label>NIK</label>
        <input type="text" name="nik" class="form-control" value="{{ $anggota->nik }}" required>
    </div>

    <div class="mb-3">
        <label>No HP</label>
        <input type="text" name="no_hp" class="form-control" value="{{ $anggota->no_hp }}" required>
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3">{{ $anggota->alamat }}</textarea>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('anggota_pendakian.index') }}" class="btn btn-secondary">Batal</a>
</form>
```

</div>
@endsection
