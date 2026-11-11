<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaPendakian;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Storage;

class AnggotaPendakianController extends Controller
{
    public function index()
    {
        $anggotas = AnggotaPendakian::with('pendaftaran')->latest()->get();
        return view('anggota_pendakian.index', compact('anggotas'));
    }

    public function create()
    {
        $pendaftaran = Pendaftaran::with('rutePendakian.gunung')->get();
        return view('anggota_pendakian.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id_pendaftaran',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|integer|min:1',
            'no_telepon' => 'required|string|max:20',
            'jenis_identitas' => 'nullable|in:KTP,SIM,KK',
            'no_identitas' => 'nullable|string|max:30',
            'foto_identitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload file jika ada
        if ($request->hasFile('foto_identitas')) {
            $path = $request->file('foto_identitas')->store('foto_anggota', 'public');
            $validated['foto_identitas'] = $path;
        }

        AnggotaPendakian::create($validated);

        return redirect()->route('anggota_pendakian.index')
                         ->with('success', 'Anggota pendakian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $anggota = AnggotaPendakian::findOrFail($id);
        $pendaftaran = Pendaftaran::with('rutePendakian.gunung')->get();
        return view('anggota_pendakian.edit', compact('anggota', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        $anggota = AnggotaPendakian::findOrFail($id);

        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,id_pendaftaran',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|integer|min:1',
            'no_telepon' => 'required|string|max:20',
            'jenis_identitas' => 'nullable|in:KTP,SIM,KK',
            'no_identitas' => 'nullable|string|max:30',
            'foto_identitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika upload baru, hapus foto lama
        if ($request->hasFile('foto_identitas')) {
            if ($anggota->foto_identitas && Storage::disk('public')->exists($anggota->foto_identitas)) {
                Storage::disk('public')->delete($anggota->foto_identitas);
            }
            $path = $request->file('foto_identitas')->store('foto_anggota', 'public');
            $validated['foto_identitas'] = $path;
        }

        $anggota->update($validated);

        return redirect()->route('anggota_pendakian.index')
                         ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = AnggotaPendakian::findOrFail($id);
        
        if ($anggota->foto_identitas && Storage::disk('public')->exists($anggota->foto_identitas)) {
            Storage::disk('public')->delete($anggota->foto_identitas);
        }

        $anggota->delete();

        return redirect()->route('anggota_pendakian.index')
                         ->with('success', 'Data anggota berhasil dihapus.');
    }
}
