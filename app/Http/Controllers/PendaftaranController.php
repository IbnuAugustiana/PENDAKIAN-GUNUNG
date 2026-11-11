<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Gunung;
use App\Models\RutePendakian;
use App\Models\AnggotaPendakian;
use App\Http\Requests\PendaftaranRequest;
use App\Mail\PendaftaranBerhasil;
use App\Services\PeminjamanApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Config as MidtransConfig;

class PendaftaranController extends Controller
{
    protected $peminjamanApiService;

    public function __construct(PeminjamanApiService $peminjamanApiService)
    {
        $this->peminjamanApiService = $peminjamanApiService;
    }

    /**
     * Tampilkan semua pendaftaran user login
     */
    public function index()
    {
        // Jika user mempunyai flag is_admin = true, tampilkan semua pendaftaran untuk manajemen admin
        if (Auth::check() && (Auth::user()->is_admin ?? false)) {
            $pendaftarans = Pendaftaran::with(['rutePendakian.gunung', 'anggotaPendakian', 'user'])
                ->latest()
                ->get();
        } else {
            // Otomatis ubah status pembayaran menjadi "lunas" untuk pendaftaran user yang belum bayar
            Pendaftaran::where('user_id', Auth::id())
                ->where('status_pembayaran', 'belum')
                ->update(['status_pembayaran' => 'lunas']);

            $pendaftarans = Pendaftaran::with(['rutePendakian.gunung', 'anggotaPendakian', 'user'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }

        return view('pendaftaran.index', compact('pendaftarans'));
    }

    /**
     * Form tambah pendaftaran
     */
    public function create($gunung_id = null, $rute_id = null)
    {
        if ($gunung_id && $rute_id) {
            $gunung = Gunung::find($gunung_id);
            $rute = RutePendakian::find($rute_id);
            return view('pendaftaran.create', compact('gunung', 'rute'));
        }

        $gunungs = Gunung::all();
        return view('pendaftaran.create', compact('gunungs'));
    }

    /**
     * Simpan pendaftaran baru dengan integrasi peminjaman alat
     */
    public function store(PendaftaranRequest $request)
    {
        try {
            // Data sudah tervalidasi oleh PendaftaranRequest

            // 🔹 Ambil harga dari rute
            $rute = RutePendakian::findOrFail($request->rute_pendakian_id);
            $biaya_simaksi = $rute->harga * $request->jumlah_pendaki;

            // 🔹 Hitung biaya peminjaman alat jika ada
            $biaya_alat = 0;
            $peminjaman_id = null;
            if ($request->has('alat_peminjaman') && !empty($request->alat_peminjaman)) {
                // Buat peminjaman otomatis melalui webhook
                $peminjamanResponse = $this->createPeminjamanOtomatis($request);
                Log::info('Peminjaman response', ['response' => $peminjamanResponse]);
                if ($peminjamanResponse['success']) {
                    $peminjaman_id = $peminjamanResponse['peminjaman_id'];
                    $biaya_alat = $peminjamanResponse['total_biaya'];
                } else {
                    // Jika gagal buat peminjaman, lanjutkan tanpa alat
                    Log::warning('Gagal membuat peminjaman otomatis', [
                        'error' => $peminjamanResponse['message'],
                        'user_id' => $request->user_id ?? auth()->id(),
                        'alat_peminjaman' => $request->alat_peminjaman
                    ]);
                }
            }

            // 🔹 Total biaya keseluruhan
            $total_biaya = $biaya_simaksi + $biaya_alat;

            // 🔹 Simpan file identitas utama
            $foto_identitas_path = null;
            if ($request->hasFile('foto_identitas')) {
                $foto_identitas_path = $request->file('foto_identitas')->store('identitas', 'public');
            }

            // 🔹 Buat pendaftaran
            $pendaftaran = Pendaftaran::create([
                'user_id' => auth()->id(),
                'rute_pendakian_id' => $request->rute_pendakian_id,
                'tanggal_pendakian' => $request->tanggal_pendakian,
                'tanggal_turun' => $request->tanggal_turun,
                'jumlah_pendaki' => $request->jumlah_pendaki,
                'jenis_identitas' => $request->jenis_identitas,
                'no_identitas' => $request->no_identitas,
                'foto_identitas' => $foto_identitas_path,
                'status' => 'pending',
                'status_pembayaran' => 'belum',
                'total_biaya' => $total_biaya,
                'peminjaman_id' => $peminjaman_id,
                'biaya_alat' => $biaya_alat,
                'status_peminjaman' => $peminjaman_id ? 'menunggu' : null,
                'status_verifikasi_peminjaman' => $peminjaman_id ? 'belum_verifikasi' : null,
            ]);

            // 🔹 Simpan anggota pendakian jika ada
            if ($request->has('anggota') && is_array($request->anggota)) {
                foreach ($request->anggota as $anggotaData) {
                    $foto_anggota_path = null;
                    if (isset($anggotaData['foto_identitas']) && $anggotaData['foto_identitas']) {
                        $foto_anggota_path = $anggotaData['foto_identitas']->store('identitas_anggota', 'public');
                    }

                    AnggotaPendakian::create([
                        'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                        'nama' => $anggotaData['nama'],
                        'jenis_kelamin' => $anggotaData['jenis_kelamin'],
                        'usia' => $anggotaData['usia'],
                        'no_telepon' => $anggotaData['no_telepon'],
                        'jenis_identitas' => $anggotaData['jenis_identitas'],
                        'no_identitas' => $anggotaData['no_identitas'],
                        'foto_identitas' => $foto_anggota_path,
                    ]);
                }
            }

            // 🔹 Mode demo: bila key Midtrans belum diisi, pendaftaran tetap berhasil
            // tanpa membuka popup pembayaran (status pembayaran diproses di halaman Pendaftaran).
            if (! $this->midtransConfigured()) {
                return response()->json([
                    'success' => true,
                    'snap_token' => null,
                    'demo' => true,
                    'message' => 'Pendaftaran berhasil disimpan.',
                    'redirect' => route('pendaftaran.index'),
                    'total_biaya' => $total_biaya,
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                    'peminjaman_id' => $peminjaman_id,
                ]);
            }

            // 🔹 Buat transaksi Midtrans
            // Set Midtrans configuration and prepare transaction details
            $this->configureMidtrans();
            $transaction_details = [
                'order_id' => 'SIMAKSI-' . $pendaftaran->id_pendaftaran . '-' . time(),
                'gross_amount' => $total_biaya,
            ];

            $customer_details = [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $request->no_telepon ?? '',
            ];

            $item_details = [
                [
                    'id' => 'simaksi-' . $pendaftaran->id_pendaftaran,
                    'price' => $biaya_simaksi,
                    'quantity' => 1,
                    'name' => 'Biaya SIMAKSI - ' . $rute->nama_rute,
                ]
            ];

            // Tambahkan item biaya alat jika ada
            if ($biaya_alat > 0) {
                $item_details[] = [
                    'id' => 'alat-' . $pendaftaran->id_pendaftaran,
                    'price' => $biaya_alat,
                    'quantity' => 1,
                    'name' => 'Biaya Peminjaman Alat Pendakian',
                ];
            }

            $transaction = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'total_biaya' => $total_biaya,
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'peminjaman_id' => $peminjaman_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating pendaftaran: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buat peminjaman otomatis melalui API
     */
    private function createPeminjamanOtomatis(Request $request)
    {
        if (! config('services.peminjaman_api.url')) {
            return ['success' => false, 'message' => 'Layanan peminjaman alat tidak tersedia'];
        }

        try {
            $alatPeminjaman = [];
            $totalBiayaAlat = 0;

            // Ambil data alat dari API untuk validasi dan perhitungan
            $alatResponse = Http::get(config('services.peminjaman_api.url') . '/alats');
            if (!$alatResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'Tidak dapat mengambil data alat dari sistem peminjaman'
                ];
            }

            $alats = $alatResponse->json();

            // Validasi dan hitung biaya alat yang dipilih
            foreach ($request->alat_peminjaman as $alatId => $jumlah) {
                if ($jumlah > 0) {
                    $alat = collect($alats)->firstWhere('id', (int)$alatId);
                    if (!$alat) {
                        continue; // Skip alat yang tidak ditemukan
                    }

                    if ($jumlah > $alat['stok']) {
                        return [
                            'success' => false,
                            'message' => "Stok alat {$alat['nama']} tidak mencukupi"
                        ];
                    }

                    $alatPeminjaman[] = [
                        'alat_id' => (int)$alatId,
                        'jumlah' => (int)$jumlah,
                        'harga' => $alat['harga']
                    ];

                    $totalBiayaAlat += $jumlah * $alat['harga'];
                }
            }

            if (empty($alatPeminjaman)) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada alat yang dipilih'
                ];
            }

            // Buat peminjaman melalui API
            $peminjamanData = [
                'user_id' => auth()->id(),
                'items' => $alatPeminjaman
            ];

            $peminjamanResponse = $this->peminjamanApiService->createPeminjaman(auth()->id(), $alatPeminjaman);

            if ($peminjamanResponse['success']) {
                return [
                    'success' => true,
                    'peminjaman_id' => $peminjamanResponse['peminjaman_id'],
                    'total_biaya' => $totalBiayaAlat
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $peminjamanResponse['message'] ?? 'Gagal membuat peminjaman'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Error creating automatic peminjaman: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat peminjaman: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cetak PDF pendaftaran
     */
    public function cetak($id)
    {
        $pendaftaran = Pendaftaran::with(['rutePendakian.gunung', 'anggotaPendakian', 'user'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pendaftaran.pdf', compact('pendaftaran'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Formulir_SIMAKSI_' . $pendaftaran->id_pendaftaran . '.pdf');
    }

    /**
     * Hapus data pendaftaran
     */
    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Hapus foto identitas jika ada (cek disk 'public')
        if ($pendaftaran->foto_identitas && Storage::disk('public')->exists($pendaftaran->foto_identitas)) {
            Storage::disk('public')->delete($pendaftaran->foto_identitas);
        }

        $pendaftaran->delete();

        return redirect()->route('pendaftaran.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    // Tambahkan method untuk edit (admin) agar menampilkan form perubahan
    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with(['rutePendakian.gunung', 'anggotaPendakian', 'user'])
            ->findOrFail($id);

        return view('pendaftaran.edit', compact('pendaftaran'));
    }

    // Tambahkan method update (admin) untuk menyimpan perubahan termasuk status
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:Menunggu,Disetujui,Dibatalkan,Selesai',
            'status_pembayaran' => 'nullable|in:Lunas,Belum',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);

        $dirty = false;
        if ($request->has('status') && $pendaftaran->status !== $request->status) {
            $pendaftaran->status = $request->status;
            $dirty = true;
        }

        if ($request->has('status_pembayaran') && $pendaftaran->status_pembayaran !== $request->status_pembayaran) {
            $pendaftaran->status_pembayaran = $request->status_pembayaran;
            $dirty = true;
        }

        // Simpan jika ada perubahan
        if ($dirty) {
            $pendaftaran->save();
            return redirect()->back()->with('success', 'Perubahan pendaftaran berhasil disimpan.');
        }

        return redirect()->back()->with('info', 'Tidak ada perubahan yang disimpan.');
    }

    public function createWithParams($gunung_id, $rute_id)
    {
        $gunung = Gunung::findOrFail($gunung_id);
        $rute = RutePendakian::findOrFail($rute_id);

        // arahkan ke view form (misal pendaftaran.create)
        return view('pendaftaran.create', compact('gunung', 'rute'));
    }

    /**
     * Halaman pembayaran (invoice) untuk satu pendaftaran.
     */
    public function bayar($id)
    {
        $pendaftaran = Pendaftaran::with(['rutePendakian.gunung', 'user'])->findOrFail($id);

        // Hanya pemilik pendaftaran atau admin yang boleh membuka invoice
        if ($pendaftaran->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $snapToken = null;
        $sudahLunas = in_array(strtolower((string) $pendaftaran->status_pembayaran), ['lunas', 'paid'], true);

        if (! $sudahLunas && $this->midtransConfigured()) {
            try {
                $this->configureMidtrans();
                $snapToken = \Midtrans\Snap::getSnapToken([
                    'transaction_details' => [
                        'order_id' => 'SIMAKSI-' . $pendaftaran->id_pendaftaran . '-' . time(),
                        'gross_amount' => (int) ($pendaftaran->total_biaya ?? 0),
                    ],
                    'customer_details' => [
                        'first_name' => $pendaftaran->user->name ?? '',
                        'email' => $pendaftaran->user->email ?? '',
                    ],
                ]);
            } catch (\Exception $e) {
                Log::warning('Gagal membuat token pembayaran: ' . $e->getMessage(), [
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                ]);
            }
        }

        return view('pendaftaran.bayar', compact('pendaftaran', 'snapToken'));
    }

    /**
     * Handle Midtrans callback
     */
    public function callback(Request $request)
    {
        try {
            $this->configureMidtrans();

            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Extract pendaftaran ID from order_id (format: SIMAKSI-{id}-{timestamp})
            $order_parts = explode('-', $order_id);
            if (count($order_parts) >= 2 && $order_parts[0] === 'SIMAKSI') {
                $pendaftaran_id = $order_parts[1];
            } else {
                Log::error('Invalid order_id format', ['order_id' => $order_id]);
                return response()->json(['status' => 'error'], 400);
            }

            $pendaftaran = Pendaftaran::find($pendaftaran_id);
            if (!$pendaftaran) {
                Log::error('Pendaftaran not found', ['pendaftaran_id' => $pendaftaran_id]);
                return response()->json(['status' => 'error'], 404);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $pendaftaran->status_pembayaran = 'belum';
                    } else {
                        $pendaftaran->status_pembayaran = 'lunas';
                    }
                }
            } elseif ($transaction == 'settlement') {
                $pendaftaran->status_pembayaran = 'lunas';
            } elseif ($transaction == 'pending') {
                $pendaftaran->status_pembayaran = 'belum';
            } elseif ($transaction == 'deny') {
                $pendaftaran->status_pembayaran = 'belum';
            } elseif ($transaction == 'expire') {
                $pendaftaran->status_pembayaran = 'belum';
            } elseif ($transaction == 'cancel') {
                $pendaftaran->status_pembayaran = 'belum';
            }

            $pendaftaran->save();

            Log::info('Midtrans callback processed', [
                'pendaftaran_id' => $pendaftaran_id,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'status_pembayaran' => $pendaftaran->status_pembayaran
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage(), [
                'request_data' => $request->all()
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Apakah key Midtrans sudah diisi di environment.
     */
    private function midtransConfigured(): bool
    {
        return (string) config('midtrans.server_key') !== '';
    }

    /**
     * Konfigurasi Midtrans berdasarkan config/midtrans.php
     */
    private function configureMidtrans(): void
    {
        // Pastikan key terisi melalui .env
        MidtransConfig::$serverKey   = config('midtrans.server_key');
        MidtransConfig::$isProduction = (bool) config('midtrans.is_production');
        MidtransConfig::$isSanitized  = (bool) config('midtrans.is_sanitized');
        MidtransConfig::$is3ds        = (bool) config('midtrans.is_3ds');
    }
}
