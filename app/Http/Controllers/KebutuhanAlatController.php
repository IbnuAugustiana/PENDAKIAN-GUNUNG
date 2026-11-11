<?php

namespace App\Http\Controllers;

use App\Models\KebutuhanAlat;
use App\Models\User;
use Illuminate\Http\Request;

class KebutuhanAlatController extends Controller
{
    public function index()
    {
        $alat = KebutuhanAlat::with('user')->get();
        return view('alat.index', compact('alat'));
    }

    public function create()
    {
        $users = User::all();
        return view('alat.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nama_alat' => 'required',
            'jumlah' => 'required|numeric',
        ]);

        KebutuhanAlat::create($request->all());
        return redirect()->route('kebutuhan-alat.index')->with('success', 'Alat berhasil ditambahkan');
    }

    public function edit(KebutuhanAlat $kebutuhan_alat)
    {
        $users = User::all();
        return view('alat.edit', compact('kebutuhan_alat','users'));
    }

    public function update(Request $request, KebutuhanAlat $kebutuhan_alat)
    {
        $request->validate([
            'id_user' => 'required',
            'nama_alat' => 'required',
            'jumlah' => 'required|numeric',
        ]);

        $kebutuhan_alat->update($request->all());
        return redirect()->route('kebutuhan-alat.index')->with('success', 'Alat berhasil diperbarui');
    }

    public function destroy(KebutuhanAlat $kebutuhan_alat)
    {
        $kebutuhan_alat->delete();
        return redirect()->route('kebutuhan-alat.index')->with('success', 'Alat berhasil dihapus');
    }
}
