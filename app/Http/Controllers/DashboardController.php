<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Gunung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Statistik utama
        $totalPendaftaran = Pendaftaran::where('user_id', $userId)->count();
        $menunggu = Pendaftaran::where('user_id', $userId)->where('status', 'pending')->count();
        $disetujui = Pendaftaran::where('user_id', $userId)->where('status', 'disetujui')->count();
        $belumBayar = Pendaftaran::where('user_id', $userId)->where('status_pembayaran', 'belum')->count();

        // Daftar pendaftaran user dengan status peminjaman
        $pendaftaranSaya = Pendaftaran::with(['rutePendakian.gunung'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Tambahkan status peminjaman dari API untuk setiap pendaftaran yang memiliki peminjaman_id
        foreach ($pendaftaranSaya as $pendaftaran) {
            if ($pendaftaran->peminjaman_id) {
                $pendaftaran->status_peminjaman = $this->getPeminjamanStatus($pendaftaran->peminjaman_id);
            }
        }

        // Gunung populer minggu ini
        $gunungPopuler = Gunung::withCount(['rutePendakian as pendaki_minggu_ini' => function ($query) {
            $query->whereHas('pendaftaran', function ($p) {
                $p->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            });
        }])
            ->with(['rutePendakian.pendaftaran'])
            ->orderByDesc('pendaki_minggu_ini')
            ->take(3)
            ->get();

        return view('user.dashboard', compact('totalPendaftaran', 'menunggu', 'disetujui', 'belumBayar', 'pendaftaranSaya', 'gunungPopuler'));
    }

    /**
     * Get peminjaman status from external API
     */
    private function getPeminjamanStatus($peminjamanId)
    {
        if (! config('services.peminjaman_api.url')) {
            return 'unknown'; // layanan peminjaman tidak dikonfigurasi
        }

        try {
            $response = Http::get(config('services.peminjaman_api.url') . '/peminjaman/' . $peminjamanId . '/status');

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] ?? 'unknown';
            }

            return 'unknown';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }
}
