@extends('layouts.main')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Anggota Pendakian</h1>
        <a href="{{ route('anggota_pendakian.create') }}" class="btn btn-primary">+ Tambah Anggota</a>
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
            <th>Nama Anggota</th>
            <th>NIK</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($anggota as $a)
            <tr>
                <td>{{ $a->id_anggota }}</td>
                <td>#{{ $a->pendaftaran->id_pendaftaran ?? '-' }}</td>
                <td>{{ $a->nama_anggota }}</td>
                <td>{{ $a->nik }}</td>
                <td>{{ $a->no_hp }}</td>
                <td>{{ $a->alamat }}</td>
                <td>
                    <a href="{{ route('anggota_pendakian.edit', $a->id_anggota) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('anggota_pendakian.destroy', $a->id_anggota) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada anggota pendakian.</td>
            </tr>
        @endforelse
    </tbody>
</table>
```

</div>
@endsection
