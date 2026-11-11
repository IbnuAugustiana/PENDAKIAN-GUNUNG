<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\RutePendakian;
use App\Models\Gunung;
use Illuminate\Http\Request;

class RutePendakianController extends Controller
{
    public function index()
    {
        // Ambil semua rute beserta nama gunung untuk admin
        $rute_pendakians = RutePendakian::with('gunung')->get();
        return view('admin.rute_pendakian.index', compact('rute_pendakians'));
    }

    public function create()
    {
        $gunungs = Gunung::all();
        return view('admin.rute_pendakian.create', compact('gunungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gunung_id' => 'required|exists:gunung,id_gunung',
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0'
        ]);

        RutePendakian::create($request->all());
        return redirect()->route('admin.rute-pendakian.index')->with('success', 'Rute pendakian berhasil ditambahkan');
    }

    public function edit(RutePendakian $rute_pendakian)
    {
        $gunungs = Gunung::all();
        return view('admin.rute_pendakian.edit', compact('rute_pendakian', 'gunungs'));
    }

    public function update(Request $request, RutePendakian $rute_pendakian)
    {
        $request->validate([
            'gunung_id' => 'required|exists:gunung,id_gunung',
            'nama_rute' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0'
        ]);

        $rute_pendakian->update($request->all());
        return redirect()->route('admin.rute-pendakian.index')->with('success', 'Rute pendakian berhasil diperbarui');
    }

    public function destroy(RutePendakian $rute_pendakian)
    {
        $rute_pendakian->delete();
        return redirect()->route('admin.rute-pendakian.index')->with('success', 'Rute pendakian berhasil dihapus');
    }

    // 🔹 Digunakan untuk dropdown dinamis berdasarkan gunung
public function getByGunung($id_gunung)
{
    // Hilangkan duplikasi nama_rute (ambil id terkecil per nama+harga)
    $rutes = \App\Models\RutePendakian::where('gunung_id', $id_gunung)
        ->selectRaw('MIN(id_rute) as id_rute, nama_rute, harga')
        ->groupBy('nama_rute', 'harga')
        ->orderBy('nama_rute')
        ->get();

    return response()->json($rutes);
}



public function showRuteByGunung($id_gunung)
{
    $gunung = Gunung::findOrFail($id_gunung);
    $rutes = RutePendakian::where('gunung_id', $id_gunung)->get();

    return view('rute_pendakian.index_by_gunung', compact('gunung', 'rutes'));
}


}
