<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gunung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GunungController extends Controller
{
    public function index()
    {
        $gunungs = Gunung::orderBy('nama_gunung')->get();
        return view('admin.gunung.index', compact('gunungs'));
    }

    public function create()
    {
        return view('admin.gunung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gunung' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'ketinggian' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'harga_simaksi' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        // handle upload gambar (opsional)
        if ($request->hasFile('gambar')) {
            $dir = public_path('images/gunung');
            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = time() . '_' . Str::slug($request->nama_gunung) . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move($dir, $filename);
            $validated['gambar'] = $filename;
        }

        Gunung::create($validated);

        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil ditambahkan');
    }

    public function edit(Gunung $gunung)
    {
        return view('admin.gunung.edit', compact('gunung'));
    }

    public function update(Request $request, Gunung $gunung)
    {
        $validated = $request->validate([
            'nama_gunung' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'ketinggian' => 'required|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'harga_simaksi' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $dir = public_path('images/gunung');
            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = time() . '_' . Str::slug($request->nama_gunung) . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move($dir, $filename);
            $validated['gambar'] = $filename;
        }

        $gunung->update($validated);

        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil diperbarui');
    }

    public function destroy(Gunung $gunung)
    {
        // opsional: hapus file gambar lama
        if ($gunung->gambar) {
            $path = public_path('images/gunung/' . $gunung->gambar);
            if (File::exists($path)) {
                @File::delete($path);
            }
        }

        $gunung->delete();
        return redirect()->route('admin.gunung.index')->with('success', 'Gunung berhasil dihapus');
    }
}

