@extends('admin.layouts.app')

@section('title', 'Detail Pendaftaran - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Detail Pendaftaran</h1>
            <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Pendaftaran:</strong> {{ $pendaftaran->id_pendaftaran }}</p>
                            <p><strong>User:</strong> {{ $pendaftaran->user->nama_lengkap }}</p>
                            <p><strong>Email:</strong> {{ $pendaftaran->user->email }}</p>
                            <p><strong>Gunung:</strong> {{ $pendaftaran->gunung->nama_gunung }}</p>
                            <p><strong>Rute:</strong> {{ $pendaftaran->rutePendakian->nama_rute }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Pendakian:</strong> {{ $pendaftaran->tanggal_pendakian }}</p>
                            <p><strong>Tanggal Turun:</strong> {{ $pendaftaran->tanggal_turun }}</p>
                            <p><strong>Jumlah Pendaki:</strong> {{ $pendaftaran->jumlah_pendaki }}</p>
                            <p><strong>Status:</strong>
                                <span class="badge badge-{{ $pendaftaran->status == 'disetujui' ? 'success' : ($pendaftaran->status == 'ditolak' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($pendaftaran->status) }}
                                </span>
                            </p>
                            <p><strong>Status Pembayaran:</strong> {{ ucfirst($pendaftaran->status_pembayaran) }}</p>
                        </div>
                    </div>

                    <hr>
                    <h6>Identitas Pendaki Utama</h6>
                    <p><strong>Nama Lengkap:</strong> {{ $pendaftaran->user->nama_lengkap }}</p>
                    <p><strong>Jenis Identitas:</strong> {{ $pendaftaran->jenis_identitas ?? 'Belum diisi' }}</p>
                    <p><strong>No Identitas:</strong> {{ $pendaftaran->no_identitas ?? 'Belum diisi' }}</p>
                    @if($pendaftaran->foto_identitas)
                        <p><strong>Foto Identitas:</strong> <a href="{{ asset('storage/' . $pendaftaran->foto_identitas) }}" target="_blank">Lihat Foto</a></p>
                    @endif

                    <hr>
                    <h6>Anggota Pendakian</h6>
                    @if($pendaftaran->anggotaPendakian->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenis Identitas</th>
                                        <th>No Identitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftaran->anggotaPendakian as $anggota)
                                    <tr>
                                        <td>{{ $anggota->nama_lengkap }}</td>
                                        <td>{{ $anggota->jenis_identitas }}</td>
                                        <td>{{ $anggota->no_identitas }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>Tidak ada anggota pendakian tambahan.</p>
                    @endif
                    
                    @if($pendaftaran->gunung && $pendaftaran->gunung->latitude && $pendaftaran->gunung->longitude)
                        <hr>
                        <h6>Lokasi Gunung</h6>
                        <div id="map" style="width:100%;height:300px;border:1px solid #ddd;border-radius:6px;"></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pendaftaran.update-status', $pendaftaran) }}" method="POST" data-confirm-message="Simpan perubahan status pendaftaran ini?" data-confirm-title="Konfirmasi Status" data-confirm-ok="Ya, simpan">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label for="status">Ubah Status Persetujuan</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="menunggu" {{ $pendaftaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ $pendaftaran->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $pendaftaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if($pendaftaran->gunung && $pendaftaran->gunung->latitude && $pendaftaran->gunung->longitude)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
        <script>
            function initMap() {
                var lat = parseFloat('{{ $pendaftaran->gunung->latitude }}');
                var lng = parseFloat('{{ $pendaftaran->gunung->longitude }}');
                var center = { lat: lat, lng: lng };
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: center,
                    zoom: 12
                });
                new google.maps.Marker({
                    position: center,
                    map: map,
                    title: '{{ $pendaftaran->gunung->nama_gunung }}'
                });
            }
        </script>
    @endif
@endpush
