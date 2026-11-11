<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Gunung;
use Illuminate\Http\Request;

class GunungController extends Controller
{
    public function search(Request $request)
{
    $query = trim($request->get('q', ''));

    if ($query === '') {
        // jika input kosong, kembalikan semua atau redirect ke index
        $gunungs = Gunung::all();
    } else {
        // cari berdasarkan nama_gunung atau kolom lain (mis: lokasi)
        // whereLike tanpa peka huruf besar/kecil: di PostgreSQL (Neon) menjadi ILIKE,
        // di MySQL tetap LIKE — hasil pencarian sama di kedua database.
        $gunungs = Gunung::whereLike('nama_gunung', "%{$query}%", caseSensitive: false)
                    ->orWhereLike('lokasi', "%{$query}%", caseSensitive: false)
                    ->get();
    }

    // kembalikan view daftar gunung (pakai view yang sudah ada)
    return view('gunung.index', compact('gunungs', 'query'));
}
    
    public function index(Request $request)
{
    $search = $request->input('search');

    $gunungs = Gunung::when($search, function ($query, $search) {
        // (perbaikan: kolomnya 'nama_gunung', bukan 'nama')
        return $query->whereLike('nama_gunung', "%{$search}%", caseSensitive: false)
                     ->orWhereLike('lokasi', "%{$search}%", caseSensitive: false);
    })->get();

    return view('gunung.index', compact('gunungs'));
}

    public function create()
    {
        return view('admin.gunung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gunung' => 'required',
            'lokasi' => 'required',
            'ketinggian' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        Gunung::create($request->only(['nama_gunung','lokasi','ketinggian','deskripsi','gambar','harga_simaksi','latitude','longitude']));
        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil ditambahkan');
    }

    public function show(Gunung $gunung)
    {
        return view('gunung.show', compact('gunung'));
    }

    public function edit(Gunung $gunung)
    {
        return view('admin.gunung.edit', compact('gunung'));
    }

    public function update(Request $request, Gunung $gunung)
    {
        $request->validate([
            'nama_gunung' => 'required',
            'lokasi' => 'required',
            'ketinggian' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $gunung->update($request->only(['nama_gunung','lokasi','ketinggian','deskripsi','gambar','harga_simaksi','latitude','longitude']));
        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil diperbarui');
    }

    public function destroy(Gunung $gunung)
    {
        $gunung->delete();
        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil dihapus');
    }

    public function adminIndex()
    {
        $gunungs = Gunung::all();
        return view('admin.gunung.index', compact('gunungs'));
    }
}
