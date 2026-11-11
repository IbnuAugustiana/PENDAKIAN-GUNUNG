<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran SIMAKSI</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #104734;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #104734;
            margin-bottom: 10px;
        }
        .success-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #104734;
            margin: 10px 0;
        }
        .info-section {
            background: #f8fffe;
            border: 1px solid #e0f2f1;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-title {
            font-weight: bold;
            color: #104734;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e8f5e9;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #104734;
            font-weight: 600;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .success-box h4 {
            color: #155724;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .success-box p {
            color: #155724;
            margin: 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">💳</div>
            <div class="logo">SIMAKSI</div>
            <h1 class="title">Pembayaran Berhasil!</h1>
            <p>Pembayaran pendaftaran pendakian Anda telah berhasil diproses.</p>
        </div>

        <div class="info-section">
            <div class="info-title">📋 Detail Pembayaran</div>
            <div class="info-item">
                <span class="label">ID Pendaftaran:</span>
                <span class="value">{{ $pendaftaran->id_pendaftaran }}</span>
            </div>
            <div class="info-item">
                <span class="label">Gunung Tujuan:</span>
                <span class="value">{{ $gunung->nama_gunung ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Rute Pendakian:</span>
                <span class="value">{{ $rute->nama_rute ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Tanggal Pembayaran:</span>
                <span class="value">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Jumlah Bayar:</span>
                <span class="value">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
            </div>

            @php
                $biayaSimaksi = $pendaftaran->rutePendakian->harga * $pendaftaran->jumlah_pendaki ?? 0;
                $biayaAlat = $pembayaran->jumlah_bayar - $biayaSimaksi;
            @endphp

            @if($biayaAlat > 0)
            <div class="info-item">
                <span class="label">Biaya SIMAKSI:</span>
                <span class="value">Rp {{ number_format($biayaSimaksi, 0, ',', '.') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Biaya Peminjaman Alat:</span>
                <span class="value">Rp {{ number_format($biayaAlat, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="info-item">
                <span class="label">Metode Pembayaran:</span>
                <span class="value">{{ $pembayaran->metode }}</span>
            </div>
            <div class="info-item">
                <span class="label">Status:</span>
                <span class="value">{{ $pembayaran->status }}</span>
            </div>
        </div>

        <div class="success-box">
            <h4>✅ Pembayaran Dikonfirmasi</h4>
            <p>
                Pembayaran Anda telah berhasil diproses. Status pendaftaran Anda akan segera diperbarui oleh admin.
                Anda akan menerima notifikasi selanjutnya setelah admin menyetujui pendaftaran.
            </p>
        </div>

        @if($pendaftaran->peminjaman_id)
        <div class="info-section">
            <div class="info-title">🛠️ Peminjaman Alat</div>
            <div class="info-item">
                <span class="label">ID Peminjaman:</span>
                <span class="value">{{ $pendaftaran->peminjaman_id }}</span>
            </div>

            @php
                // Ambil detail alat yang dipinjam dari API eksternal
                $alatDipinjam = [];
                try {
                    $response = \Illuminate\Support\Facades\Http::get(rtrim((string) config('services.peminjaman_api.url'), '/') . '/peminjaman/' . $pendaftaran->peminjaman_id);
                    if ($response->successful()) {
                        $peminjamanData = $response->json();
                        if (isset($peminjamanData['items'])) {
                            foreach ($peminjamanData['items'] as $item) {
                                $alatDipinjam[] = [
                                    'nama' => $item['alat']['nama'] ?? $item['alat']['nama_alat'] ?? 'Alat pendakian',
                                    'jumlah' => $item['quantity'] ?? $item['jumlah'] ?? 1,
                                    'harga' => $item['alat']['harga'] ?? $item['alat']['harga_sewa'] ?? 0
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Jika gagal ambil data, skip saja
                }
            @endphp

            @if(count($alatDipinjam) > 0)
            <div class="info-item">
                <span class="label" style="font-weight: bold; color: #104734;">Alat yang Dipinjam:</span>
                <span class="value"></span>
            </div>
            @foreach($alatDipinjam as $alat)
            <div class="info-item" style="padding-left: 20px; font-size: 14px;">
                <span class="label">{{ $alat['nama'] }} ({{ $alat['jumlah'] }}x)</span>
                <span class="value">Rp {{ number_format($alat['harga'] * $alat['jumlah'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            @endif

            <p style="margin-top: 10px; font-size: 14px; color: #666;">
                Pastikan untuk mengkonfirmasi peminjaman alat di pos pendakian sebelum masuk kawasan konservasi.
            </p>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/pendaftaran') }}" class="btn">Lihat Status Pendaftaran</a>
            <a href="{{ url('/panduan') }}" class="btn" style="background: #17a2b8;">Baca Panduan</a>
        </div>

        <div class="footer">
            <p>
                Email ini dikirim secara otomatis oleh sistem SIMAKSI.<br>
                Jika ada pertanyaan, hubungi admin melalui website.
            </p>
            <p style="margin-top: 10px;">
                <strong>SIMAKSI - Sistem Informasi Manajemen Akses Konservasi Indonesia</strong>
            </p>
        </div>
    </div>
</body>
</html>
