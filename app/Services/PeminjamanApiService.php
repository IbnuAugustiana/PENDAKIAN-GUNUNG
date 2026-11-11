<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PeminjamanApiService
{
    protected $baseUrl;
    protected $timeout = 10;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.peminjaman_api.url'), '/');
    }

    /**
     * API peminjaman alat adalah aplikasi terpisah. Bila PEMINJAMAN_API_URL
     * tidak diisi (mis. saat di-hosting tanpa layanan itu), jangan melakukan
     * request sama sekali agar halaman tidak menunggu sampai timeout.
     */
    protected function isConfigured(): bool
    {
        return $this->baseUrl !== '';
    }

    /**
     * Get all available equipment from peminjaman API
     */
    public function getAvailableAlats()
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/alats");

            if ($response->successful()) {
                $alats = $response->json();

                // Filter only alats with stok > 0
                return collect($alats)->filter(function ($alat) {
                    return $alat['stok'] > 0;
                })->values();
            }

            Log::error('Failed to fetch alats from peminjaman API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching alats from peminjaman API', [
                'message' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Create a new loan request via webhook
     */
    public function createPeminjaman($userId, array $items)
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'Layanan peminjaman alat tidak tersedia'];
        }

        try {
            // Generate unique peminjaman_id untuk SIMAKSI
            $peminjamanId = 'SIMAKSI_' . time() . '_' . $userId;

            $currentUser = auth()->user();
            $webhookData = [
                'user_id' => $userId,
                'items' => $items,
                'peminjaman_id' => $peminjamanId,
                'webhook_secret' => config('services.peminjaman_api.webhook_secret'),
                // kirim data user opsional agar peminjaman_alat bisa fallback tanpa harus call-back ke SIMAKSI
                'user_name' => $currentUser->name ?? null,
                'user_email' => $currentUser->email ?? null,
                'user_phone' => $currentUser->phone ?? null,
            ];

            $response = Http::timeout($this->timeout)->post("{$this->baseUrl}/webhooks/create-peminjaman", $webhookData);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data,
                    'peminjaman_id' => $peminjamanId,
                    'total_biaya' => $data['total_biaya'] ?? 0
                ];
            }

            Log::error('Failed to create peminjaman via webhook', [
                'status' => $response->status(),
                'body' => $response->body(),
                'user_id' => $userId,
                'items' => $items,
                'peminjaman_id' => $peminjamanId
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create loan request via webhook',
                'status' => $response->status(),
                'body' => $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Exception while creating peminjaman via webhook', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'items' => $items
            ]);

            return [
                'success' => false,
                'message' => 'Exception occurred while creating loan request'
            ];
        }
    }

    /**
     * Get all loans (admin only)
     */
    public function getAllPeminjamans()
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/admin/peminjamans");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch peminjamans from API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching peminjamans', [
                'message' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Verify a loan (admin only)
     */
    public function verifyPeminjaman($peminjamanId)
    {
        if (! $this->isConfigured()) {
            return ['success' => false, 'message' => 'Layanan peminjaman alat tidak tersedia'];
        }

        try {
            $response = Http::timeout($this->timeout)->patch("{$this->baseUrl}/admin/peminjamans/{$peminjamanId}/verifikasi");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('Failed to verify peminjaman', [
                'status' => $response->status(),
                'body' => $response->body(),
                'peminjaman_id' => $peminjamanId
            ]);

            return [
                'success' => false,
                'message' => 'Failed to verify loan'
            ];
        } catch (\Exception $e) {
            Log::error('Exception while verifying peminjaman', [
                'message' => $e->getMessage(),
                'peminjaman_id' => $peminjamanId
            ]);

            return [
                'success' => false,
                'message' => 'Exception occurred while verifying loan'
            ];
        }
    }

    /**
     * Get loan status by ID
     */
    public function getPeminjamanStatus($peminjamanId)
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/peminjaman/{$peminjamanId}/status");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch peminjaman status', [
                'status' => $response->status(),
                'body' => $response->body(),
                'peminjaman_id' => $peminjamanId
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception while fetching peminjaman status', [
                'message' => $e->getMessage(),
                'peminjaman_id' => $peminjamanId
            ]);

            return null;
        }
    }
}
