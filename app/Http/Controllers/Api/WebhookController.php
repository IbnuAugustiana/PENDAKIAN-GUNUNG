<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Pendaftaran;

class WebhookController extends Controller
{
    /**
     * Handle peminjaman status webhook from external system
     */
    public function handlePeminjamanStatus(Request $request)
    {
        try {
            // Verifikasi webhook signature
            $signature = $request->header('X-Webhook-Signature');
            $webhookSecret = config('services.peminjaman_api.webhook_secret');

            if (!$signature || !$webhookSecret) {
                Log::error('Webhook signature or secret missing');
                return response()->json(['error' => 'Invalid webhook configuration'], 401);
            }

            // Generate expected signature
            $expectedSignature = hash_hmac('sha256', json_encode($request->all()), $webhookSecret);

            if (!hash_equals($signature, $expectedSignature)) {
                Log::error('Invalid webhook signature', [
                    'received' => $signature,
                    'expected' => $expectedSignature
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Validasi request
            $request->validate([
                'peminjaman_id' => 'required|string',
                'status' => 'required|string|in:menunggu,disetujui,ditolak,selesai',
                'status_verifikasi' => 'nullable|string|in:belum_verifikasi,sedang_verifikasi,terverifikasi',
                'total_biaya' => 'nullable|numeric',
                'timestamp' => 'nullable|date'
            ]);

            $peminjamanId = $request->peminjaman_id;
            $status = $request->status;
            $statusVerifikasi = $request->status_verifikasi ?? 'belum_verifikasi';
            $totalBiaya = $request->total_biaya;

            // Cari pendaftaran berdasarkan peminjaman_id
            $pendaftaran = Pendaftaran::where('peminjaman_id', $peminjamanId)->first();

            if (!$pendaftaran) {
                Log::error('Pendaftaran not found for peminjaman_id', ['peminjaman_id' => $peminjamanId]);
                return response()->json(['error' => 'Pendaftaran not found'], 404);
            }

            // Update status peminjaman di SIMAKSI
            $pendaftaran->status_peminjaman = $status;
            $pendaftaran->status_verifikasi_peminjaman = $statusVerifikasi;

            if ($totalBiaya) {
                // Update total biaya jika ada perubahan dari sistem peminjaman
                $pendaftaran->total_biaya = $pendaftaran->total_biaya - $pendaftaran->biaya_alat + $totalBiaya;
            }

            $pendaftaran->save();

            Log::info('Peminjaman status updated via webhook', [
                'peminjaman_id' => $peminjamanId,
                'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                'status' => $status,
                'status_verifikasi' => $statusVerifikasi
            ]);

            // Broadcast status update ke frontend (jika menggunakan real-time)
            // broadcast(new PeminjamanStatusUpdated($pendaftaran));

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman status updated successfully',
                'pendaftaran_id' => $pendaftaran->id_pendaftaran
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
