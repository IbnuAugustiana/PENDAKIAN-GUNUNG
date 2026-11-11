<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RutePendakian;
use App\Models\Gunung;

class RutePendakianController extends Controller
{
    // Menampilkan semua rute (untuk halaman user)
    public function index()
    {
        $rutes = RutePendakian::with('gunung')->get();
        return view('rute_pendakian.index', compact('rutes'));
    }

    // Menampilkan rute berdasarkan gunung
    public function showRuteByGunung($id_gunung)
    {
        $gunung = Gunung::findOrFail($id_gunung);
        $rutes = RutePendakian::where('gunung_id', $id_gunung)->get();
        return view('rute_pendakian.index_by_gunung', compact('gunung', 'rutes'));
    }

    // API JSON untuk dropdown dinamis
    public function getByGunung($id_gunung)
    {
        // Hilangkan duplikasi nama_rute (ambil id terkecil per nama+harga)
        $rutes = RutePendakian::where('gunung_id', $id_gunung)
            ->selectRaw('MIN(id_rute) as id_rute, nama_rute, harga')
            ->groupBy('nama_rute', 'harga')
            ->orderBy('nama_rute')
            ->get();

        return response()->json($rutes);
    }
}
