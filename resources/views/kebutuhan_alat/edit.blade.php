@extends('layouts.main')

@section('content')

<div class="container">
    <h1>Edit Kebutuhan Alat</h1>

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

<form action="{{ route('kebutuhan_alat.update', $kebutuhan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Alat</label>
        <input type="text" name="nama_alat" class="form-control" value="{{ $kebutuhan->nama_alat }}" required>
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" value="{{ $kebutuhan->jumlah }}" required>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('kebutuhan_alat.index') }}" class="btn btn-secondary">Batal</a>
</form>
```

</div>
@endsection
