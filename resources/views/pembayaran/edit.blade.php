@extends('layouts.main')

@section('content')

<div class="container">
    <h1>Edit Pembayaran</h1>

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

<form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>ID Pendaftaran</label>
        <select name="id_pendaftaran" class="form-control" required>
            @foreach($pendaftaran as $pd)
                <option value="{{ $pd->id_pendaftaran }}" 
                    {{ $pembayaran->id_pendaftaran == $pd->id_pendaftaran ? 'selected' : '' }}>
                    {{ $pd->id_pendaftaran }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Jumlah Bayar</label>
        <input type="number" name="jumlah_bayar" class="form-control" value="{{ $pembayaran->jumlah_bayar }}" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Bayar</label>
        <input type="date" name="tanggal_bayar" class="form-control" value="{{ $pembayaran->tanggal_bayar }}" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="pending" {{ $pembayaran->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="lunas" {{ $pembayaran->status == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="gagal" {{ $pembayaran->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
        </select>
    </div>

    <button class="btn btn-success">Update</button>
    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
</form>
```

</div>
@endsection
