<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Gunung;
use App\Models\RutePendakian;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Services\PeminjamanApiService;

class AdminController extends Controller
{
    protected $peminjamanApiService;

    public function __construct(PeminjamanApiService $peminjamanApiService)
    {
        $this->peminjamanApiService = $peminjamanApiService;
    }

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        // Get peminjaman stats from API
        $peminjamans = collect($this->peminjamanApiService->getAllPeminjamans());
        $total_peminjaman = $peminjamans->count();
        $peminjaman_menunggu = $peminjamans->where('status', 'menunggu')->count();
        $peminjaman_disetujui = $peminjamans->where('status', 'disetujui')->count();

        $stats = [
            'total_users' => User::count(),
            'total_gunung' => Gunung::count(),
            'total_rute' => RutePendakian::count(),
            'total_pendaftaran' => Pendaftaran::count(),
            'pendaftaran_pending' => Pendaftaran::where('status', 'pending')->count(),
            'pendaftaran_disetujui' => Pendaftaran::where('status', 'disetujui')->count(),
            'total_peminjaman' => $total_peminjaman,
            'peminjaman_menunggu' => $peminjaman_menunggu,
            'peminjaman_disetujui' => $peminjaman_disetujui,
        ];

        $recent_pendaftaran = Pendaftaran::with(['user', 'gunung', 'rutePendakian'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_pendaftaran'));
    }

    /**
     * Display user management page.
     */
    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Update user data.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Delete user.
     */
    public function destroyUser(User $user)
    {
        // Cek jika user memiliki data pendaftaran
        if ($user->pendaftarans()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus user karena memiliki data pendaftaran.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Display pendaftaran management page (for admin).
     */
    public function pendaftaran()
    {
        $pendaftarans = Pendaftaran::with(['user', 'gunung', 'rutePendakian'])
            ->latest()
            ->get();

        return view('admin.pendaftaran.index', compact('pendaftarans'));
    }

    /**
     * Show pendaftaran detail.
     */
    public function showPendaftaran($id)
    {
        $pendaftaran = Pendaftaran::with(['user', 'gunung', 'rutePendakian', 'anggotaPendakian'])
          ->findOrFail($id);

        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }


    /**
     * Update pendaftaran status.
     */
    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $oldStatus = $pendaftaran->status;
        $newStatus = $request->status;

        // Jika status diubah menjadi 'ditolak' dan sebelumnya sudah dibayar
        if ($newStatus === 'ditolak' && $pendaftaran->status_pembayaran === 'sudah') {
            // Proses refund via Midtrans
            $this->processRefund($pendaftaran);
        }

        $pendaftaran->update([
            'status' => $newStatus,
        ]);

        $message = 'Status pendaftaran berhasil diperbarui.';
        if ($newStatus === 'ditolak' && $oldStatus !== 'ditolak') {
            $message .= ' Refund pembayaran telah diproses.';
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Process refund via Midtrans API
     */
    private function processRefund(Pendaftaran $pendaftaran)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $orderId = $pendaftaran->snap_token ?? 'ORDER-' . $pendaftaran->id_pendaftaran;

            // Refund request ke Midtrans
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
            ])->post("https://app.sandbox.midtrans.com/v2/{$orderId}/refund", [
                'refund_key' => 'refund-' . time(),
                'amount' => $pendaftaran->total_biaya,
                'reason' => 'Pendaftaran ditolak oleh admin'
            ]);

            if ($response->successful()) {
                // Update status pembayaran menjadi 'refund'
                $pendaftaran->update(['status_pembayaran' => 'refund']);
            } else {
                // Log error jika refund gagal
                \Log::error('Midtrans refund failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Refund processing error: ' . $e->getMessage());
        }
    }

    /**
     * Update pendaftaran data.
     */
    public function updatePendaftaran(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $pendaftaran->update(['status' => strtolower($request->status)]);

        return redirect()->route('admin.pendaftaran.show', $pendaftaran->id_pendaftaran)
            ->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Delete pendaftaran.
     */
    public function destroyPendaftaran(Pendaftaran $pendaftaran)
    {
        $pendaftaran->delete();

        return redirect()->route('admin.pendaftaran.index')
            ->with('success', 'Pendaftaran berhasil dihapus.');
    }

    /**
     * Display peminjaman management page (for admin).
     */
    public function peminjamanIndex()
    {
        $peminjamans = $this->peminjamanApiService->getAllPeminjamans();

        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Display pembayaran management page (for admin).
     */
    public function pembayaranIndex()
    {
        $pembayarans = Pendaftaran::with(['user', 'gunung', 'rutePendakian'])
            ->where('status_pembayaran', 'lunas')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * Update peminjaman status from admin panel
     */
    public function updatePeminjamanStatus(Request $request, $peminjamanId)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak',
        ]);

        $result = $this->peminjamanApiService->verifyPeminjaman($peminjamanId);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status peminjaman.');
    }
}
