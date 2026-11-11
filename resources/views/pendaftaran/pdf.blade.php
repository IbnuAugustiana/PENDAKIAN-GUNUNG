<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulir Pendaftaran SIMAKSI</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      margin: 40px;
      font-size: 13px;
      color: #222;
    }
    h1, h2, h3 {
      color: #155724;
      text-align: center;
      margin-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px 10px;
      text-align: left;
      vertical-align: top;
    }
    th {
      background: #e8f5e9;
      font-weight: bold;
    }
    .header {
      text-align: center;
      border-bottom: 3px solid #388e3c;
      margin-bottom: 30px;
      padding-bottom: 10px;
    }
    .footer {
      text-align: right;
      font-size: 12px;
      color: #666;
      margin-top: 40px;
    }
    .identitas {
      margin-bottom: 15px;
    }
    .identitas img {
      width: 120px;
      height: auto;
      border: 1px solid #aaa;
      margin-top: 5px;
    }
    .signature {
      margin-top: 50px;
      text-align: right;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1> FORMULIR PENDAFTARAN SIMAKSI</h1>
    <h3>{{ $pendaftaran->rutePendakian->gunung->nama_gunung ?? '-' }}</h3>
  </div>

  <h3>Data Pendaftar</h3>
  <table>
    <tr><th>Nama Pendaftar</th><td>{{ $pendaftaran->user->name ?? '-' }}</td></tr>
    <tr><th>Email</th><td>{{ $pendaftaran->user->email ?? '-' }}</td></tr>
    <tr><th>Jenis Identitas</th><td>{{ strtoupper($pendaftaran->jenis_identitas ?? '-') }}</td></tr>
    <tr><th>Nomor Identitas</th><td>{{ $pendaftaran->no_identitas ?? '-' }}</td></tr>
    <tr>
      <th>Foto Identitas</th>
      <td>
        @if($pendaftaran->foto_identitas)
          <img src="{{ public_path('storage/' . $pendaftaran->foto_identitas) }}" alt="Foto Identitas">
        @else
          <em>Tidak ada</em>
        @endif
      </td>
    </tr>
  </table>

  <h3>Detail Pendakian</h3>
  <table>
    <tr><th>Gunung</th><td>{{ $pendaftaran->rutePendakian->gunung->nama_gunung ?? '-' }}</td></tr>
    <tr><th>Rute Pendakian</th><td>{{ $pendaftaran->rutePendakian->nama_rute ?? '-' }}</td></tr>
    <tr><th>Tanggal Naik</th><td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendakian)->format('d M Y') }}</td></tr>
    <tr><th>Tanggal Turun</th><td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_turun)->format('d M Y') }}</td></tr>
    <tr><th>Jumlah Pendaki</th><td>{{ $pendaftaran->jumlah_pendaki }}</td></tr>
    <tr><th>Total Biaya</th><td>Rp{{ number_format($pendaftaran->total_biaya, 0, ',', '.') }}</td></tr>
    <tr><th>Status Pembayaran</th><td><strong>{{ strtoupper($pendaftaran->status_pembayaran) }}</strong></td></tr>
  </table>

  <h3>Daftar Anggota Pendakian</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Umur</th>
        <th>Jenis Kelamin</th>
        <th>No Identitas</th>
        <th>No HP</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pendaftaran->anggotaPendakian as $index => $a)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $a->nama }}</td>
          <td>{{ $a->usia }}</td>
          <td>{{ $a->jenis_kelamin }}</td>
          <td>{{ $a->no_identitas }}</td>
          <td>{{ $a->no_telepon }}</td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center">Tidak ada anggota tambahan</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="signature">
    <p>Disetujui oleh Admin</p>
    <p><strong>{{ $pendaftaran->status == 'Disetujui' ? '✅ Sudah Disetujui' : '⏳ Menunggu Persetujuan' }}</strong></p>
  </div>

  <div class="footer">
    <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
  </div>
</body>
</html>
