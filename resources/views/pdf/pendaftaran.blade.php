<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0e3d2c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0e3d2c;
            margin-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #155c3b;
        }

        .content {
            margin: 20px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            color: #0e3d2c;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 150px;
            font-weight: bold;
            font-size: 12px;
        }

        .info-value {
            flex: 1;
            font-size: 12px;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            display: inline-block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SIMAKSI.COM</div>
        <div class="title">{{ $title }}</div>
        <div>No. Pendaftaran: {{ $pendaftaran->id_pendaftaran }}</div>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">INFORMASI PENDAFTAR</div>
            <div class="info-row">
                <div class="info-label">Nama Lengkap:</div>
                <div class="info-value">{{ $pendaftaran->user->nama_lengkap ?? $pendaftaran->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $pendaftaran->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Identitas:</div>
                <div class="info-value">{{ strtoupper($pendaftaran->jenis_identitas) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">No. Identitas:</div>
                <div class="info-value">{{ $pendaftaran->no_identitas }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">INFORMASI PENDAKIAN</div>
            <div class="info-row">
                <div class="info-label">Gunung:</div>
                <div class="info-value">{{ $pendaftaran->gunung->nama_gunung }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Rute:</div>
                <div class="info-value">{{ $pendaftaran->rutePendakian->nama_rute }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pendakian:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendakian)->format('d M Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Turun:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_turun)->format('d M Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Jumlah Pendaki:</div>
                <div class="info-value">{{ $pendaftaran->jumlah_pendaki }} orang</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">STATUS PENDAFTARAN</div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-approved">{{ strtoupper($pendaftaran->status) }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Pembayaran:</div>
                <div class="info-value">{{ strtoupper($pendaftaran->status_pembayaran) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Biaya:</div>
                <div class="info-value">Rp {{ number_format($pendaftaran->total_biaya, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($pendaftaran->peminjaman_id)
        <div class="section">
            <div class="section-title">PEMINJAMAN ALAT</div>
            <div class="info-row">
                <div class="info-label">ID Peminjaman:</div>
                <div class="info-value">{{ $pendaftaran->peminjaman_id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Peminjaman:</div>
                <div class="info-value">Aktif</div>
            </div>
            <div style="margin-top: 10px;">
                <div class="info-label" style="margin-bottom: 5px;">Detail Alat:</div>
                <div style="font-size: 11px; color: #666;">
                    @php
                        // Ambil data alat dari API eksternal
                        try {
                            $alatResponse = Illuminate\Support\Facades\Http::get(rtrim((string) config('services.peminjaman_api.url'), '/') . '/peminjaman/' . $pendaftaran->peminjaman_id);
                            if ($alatResponse->successful()) {
                                $peminjamanData = $alatResponse->json();
                                if (isset($peminjamanData['items']) && is_array($peminjamanData['items'])) {
                                    foreach ($peminjamanData['items'] as $item) {
                                        echo '<div>• ' . ($item['nama_alat'] ?? 'Alat') . ' (Qty: ' . ($item['jumlah'] ?? 0) . ')</div>';
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            echo '<div>Data alat tidak dapat dimuat</div>';
                        }
                    @endphp
                </div>
            </div>
        </div>
        @endif

        @if($pendaftaran->anggotaPendakian->count() > 0)
        <div class="section">
            <div class="section-title">ANGGOTA PENDAKIAN</div>
            @foreach($pendaftaran->anggotaPendakian as $anggota)
            <div style="margin-bottom: 10px; padding: 10px; border: 1px solid #eee;">
                <div class="info-row">
                    <div class="info-label">Nama:</div>
                    <div class="info-value">{{ $anggota->nama_anggota }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Hubungan:</div>
                    <div class="info-value">{{ $anggota->hubungan }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP:</div>
                    <div class="info-value">{{ $anggota->no_hp }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="signature">
        <div>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</div>
        <div style="margin-top: 40px;">
            <div class="signature-line"></div>
            <div style="font-size: 12px;">Admin SIMAKSI.COM</div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini merupakan bukti resmi pendaftaran pendakian di SIMAKSI.COM</p>
        <p>SIMPAN DOKUMEN INI SEBAGAI BUKTI RESMI PENDAFTARAN ANDA</p>
    </div>
</body>
</html>
