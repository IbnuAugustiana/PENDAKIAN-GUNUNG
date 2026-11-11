@extends('layouts.main')

@section('content')

<div class="container">
    <h1>Tambah Kebutuhan Alat</h1>

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

<form action="{{ route('kebutuhan_alat.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nama Alat</label>
        <input type="text" name="nama_alat" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" required>
    </div>

    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('kebutuhan_alat.index') }}" class="btn btn-secondary">Batal</a>
</form>
```

</div>
@endsection
