<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanStatusController extends Controller
{
    /**
     * Get peminjaman status for current user
     */
    public function getUserPeminjamanStatus()
    {
        $user = Auth::user();

        // Get pendaftaran yang memiliki peminjaman_id
        $pendaftarans = Pendaftaran::with(['rutePendakian.gunung'])
            ->where('user_id', $user->id)
            ->whereNotNull('peminjaman_id')
            ->latest()
            ->get();

        $peminjamanData = [];

        foreach ($pendaftarans as $pendaftaran) {
            if ($pendaftaran->peminjaman_id) {
                // Get status dari API peminjaman
                $status = $this->getPeminjamanStatusFromAPI($pendaftaran->peminjaman_id);

                $peminjamanData[] = [
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                    'peminjaman_id' => $pendaftaran->peminjaman_id,
                    'gunung' => $pendaftaran->rutePendakian->gunung->nama_gunung ?? 'Unknown',
                    'rute' => $pendaftaran->rutePendakian->nama_rute ?? 'Unknown',
                    'tanggal_pendakian' => $pendaftaran->tanggal_pendakian,
                    'status' => $status,
                    'created_at' => $pendaftaran->created_at
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $peminjamanData
        ]);
    }

    /**
     * Get peminjaman status from external API
     */
    private function getPeminjamanStatusFromAPI($peminjamanId)
    {
        if (! config('services.peminjaman_api.url')) {
            return 'unknown'; // layanan peminjaman tidak dikonfigurasi
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get(config('services.peminjaman_api.url') . '/peminjaman/' . $peminjamanId . '/status');

            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] ?? 'unknown';
            }

            return 'unknown';
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting peminjaman status: ' . $e->getMessage());
            return 'unknown';
        }
    }
}
