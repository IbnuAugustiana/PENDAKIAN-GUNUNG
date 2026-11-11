<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PeminjamanApiService;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    protected $peminjamanApiService;

    public function __construct(PeminjamanApiService $peminjamanApiService)
    {
        $this->peminjamanApiService = $peminjamanApiService;
    }

    /**
     * Display admin peminjaman dashboard
     */
    public function index()
    {
        $peminjamans = collect($this->peminjamanApiService->getAllPeminjamans());

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Redirect to external peminjaman system for verification
     */
    public function verify($id)
    {
        $url = rtrim((string) config('services.peminjaman_api.base_url'), '/') . '/admin/peminjaman/' . $id;
        return redirect()->away($url);
    }

    /**
     * Show loan details
     */
    public function show($id)
    {
        // Get loan details from API
        $peminjaman = collect($this->peminjamanApiService->getAllPeminjamans())
            ->firstWhere('id', $id);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Peminjaman tidak ditemukan');
        }

        return view('admin.peminjaman.show', compact('peminjaman'));
    }
}
