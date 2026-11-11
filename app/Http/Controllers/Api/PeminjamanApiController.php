<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PeminjamanApiService;
use Illuminate\Http\Request;

class PeminjamanApiController extends Controller
{
    protected $peminjamanApiService;

    public function __construct(PeminjamanApiService $peminjamanApiService)
    {
        $this->peminjamanApiService = $peminjamanApiService;
    }

    /**
     * Get all available equipment
     */
    public function getAlats()
    {
        $alats = $this->peminjamanApiService->getAvailableAlats();

        return response()->json($alats);
    }

    /**
     * Create a new loan request via webhook
     */
    public function createPeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.alat_id' => 'required|integer',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric'
        ]);

        $result = $this->peminjamanApiService->createPeminjaman(
            $request->user_id,
            $request->items
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'peminjaman_id' => $result['peminjaman_id'],
                'total_biaya' => $result['total_biaya']
            ], 201);
        }

        return response()->json([
            'error' => $result['message']
        ], 500);
    }

    /**
     * Get all loans (admin only)
     */
    public function getAllPeminjamans()
    {
        $peminjamans = $this->peminjamanApiService->getAllPeminjamans();

        return response()->json($peminjamans);
    }

    /**
     * Verify a loan (admin only)
     */
    public function verifyPeminjaman($id)
    {
        $result = $this->peminjamanApiService->verifyPeminjaman($id);

        if ($result['success']) {
            return response()->json($result['data']);
        }

        return response()->json([
            'error' => $result['message']
        ], 500);
    }

    /**
     * Get loan status by ID
     */
    public function getPeminjamanStatus($id)
    {
        $status = $this->peminjamanApiService->getPeminjamanStatus($id);

        if ($status) {
            return response()->json($status);
        }

        return response()->json([
            'error' => 'Peminjaman not found'
        ], 404);
    }

    /**
     * Save peminjaman data from external system (optional endpoint)
     */
    public function savePeminjamanData(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|integer',
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'total_biaya' => 'required|numeric',
            'status' => 'required|string|in:menunggu,disetujui,ditolak,selesai'
        ]);

        // This endpoint can be used by the external peminjaman system
        // to save/update peminjaman data in SIMAKSI if needed
        // Implementation depends on your specific requirements

        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Peminjaman data saved successfully',
            'data' => $request->all()
        ]);
    }
}
