@extends('layouts.main')

@section('content')

<div class="container text-center">
    <h1 class="mb-4">Selamat Datang di Aplikasi Simaksi Gunung</h1>
    <p class="lead">Kelola data pendakian, pendaftaran, kebutuhan alat, dan pembayaran secara mudah.</p>

```
<div class="row mt-5">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Gunung</h5>
                <p class="card-text">Lihat dan kelola daftar gunung yang tersedia untuk pendakian.</p>
                <a href="{{ route('gunung.index') }}" class="btn btn-primary">Lihat Gunung</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Pendaftaran</h5>
                <p class="card-text">Daftar pendakian baru atau kelola pendaftaran yang ada.</p>
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-success">Daftar Pendakian</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Pembayaran</h5>
                <p class="card-text">Kelola pembayaran pendaftaran pendakian secara praktis.</p>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-warning">Kelola Pembayaran</a>
            </div>
        </div>
    </div>
</div>
```

</div>
@endsection
