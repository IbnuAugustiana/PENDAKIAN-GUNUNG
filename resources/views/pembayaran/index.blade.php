@extends('layouts.main')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Pembayaran</h1>
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">+ Tambah Pembayaran</a>
    </div>

```
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pendaftaran</th>
            <th>Jumlah Bayar</th>
            <th>Tanggal Bayar</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pembayaran as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->pendaftaran->id_pendaftaran ?? '-' }}</td>
                <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                <td>{{ $p->tanggal_bayar }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>
                    <a href="{{ route('pembayaran.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('pembayaran.destroy', $p->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data pembayaran.</td>
            </tr>
        @endforelse
    </tbody>
</table>
```

</div>
@endsection
