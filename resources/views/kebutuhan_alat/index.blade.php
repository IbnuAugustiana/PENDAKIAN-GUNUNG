@extends('layouts.main')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Kebutuhan Alat</h1>
        <a href="{{ route('kebutuhan_alat.create') }}" class="btn btn-primary">+ Tambah Kebutuhan</a>
    </div>

```
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Nama Alat</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($kebutuhan as $k)
            <tr>
                <td>{{ $k->id }}</td>
                <td>{{ $k->user->name ?? '-' }}</td>
                <td>{{ $k->nama_alat }}</td>
                <td>{{ $k->jumlah }}</td>
                <td>
                    <a href="{{ route('kebutuhan_alat.edit', $k->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('kebutuhan_alat.destroy', $k->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada kebutuhan alat.</td>
            </tr>
        @endforelse
    </tbody>
</table>
```

</div>
@endsection
