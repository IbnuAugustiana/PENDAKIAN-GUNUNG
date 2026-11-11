<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Mail\PembayaranBerhasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PembayaranController extends Controller
{
    /**
     * Tampilkan semua data pembayaran (admin)
     */
    public function index()
    {
        // Ambil pembayaran beserta relasi pendaftaran, user, gunung, dan rute
        $pembayarans = Pembayaran::with([
            'pendaftaran.user',
            'pendaftaran.gunung',
            'pendaftaran.rutePendakian'
        ])->latest()->get();

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    /**
     * Form tambah pembayaran manual (opsional admin)
     */
    public function create()
    {
        $pendaftarans = Pendaftaran::with(['user', 'rutePendakian.gunung'])->get();
        return view('admin.pembayaran.create', compact('pendaftarans'));
    }

    /**
     * Simpan data pembayaran (manual oleh admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id_pendaftaran',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode' => 'required|string|max:100',
            'status' => 'required|in:Lunas,Belum',
        ]);

        $pembayaran = Pembayaran::create([
            'pendaftaran_id' => $request->pendaftaran_id,
            'user_id' => Pendaftaran::find($request->pendaftaran_id)->user_id ?? null,
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode' => $request->metode,
            'status' => $request->status,
        ]);

        // Kirim email konfirmasi pembayaran jika status lunas
        if ($request->status === 'Lunas') {
            try {
                Mail::to($pembayaran->pendaftaran->user->email)->send(new PembayaranBerhasil($pembayaran));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal mengirim email konfirmasi pembayaran: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail pembayaran
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pendaftaran.user', 'pendaftaran.rutePendakian.gunung']);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Form edit pembayaran
     */
    public function edit(Pembayaran $pembayaran)
    {
        $pendaftarans = Pendaftaran::with(['user', 'rutePendakian.gunung'])->get();
        return view('admin.pembayaran.edit', compact('pembayaran', 'pendaftarans'));
    }

    /**
     * Update pembayaran
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id_pendaftaran',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode' => 'required|string|max:100',
            'status' => 'required|in:Lunas,Belum',
        ]);

        $oldStatus = $pembayaran->status;

        $pembayaran->update([
            'pendaftaran_id' => $request->pendaftaran_id,
            'user_id' => Pendaftaran::find($request->pendaftaran_id)->user_id ?? null,
            'jumlah_bayar' => $request->jumlah_bayar,
            'metode' => $request->metode,
            'status' => $request->status,
        ]);

        // Kirim email konfirmasi pembayaran jika status berubah menjadi lunas
        if ($oldStatus !== 'Lunas' && $request->status === 'Lunas') {
            try {
                Mail::to($pembayaran->pendaftaran->user->email)->send(new PembayaranBerhasil($pembayaran));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal mengirim email konfirmasi pembayaran: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Hapus data pembayaran
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('admin.pembayaran.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
