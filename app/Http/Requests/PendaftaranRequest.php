<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rute_pendakian_id' => 'required|exists:rute_pendakian,id_rute',
            'tanggal_pendakian' => 'required|date|after_or_equal:today',
            'tanggal_turun' => 'nullable|date|after:tanggal_pendakian',
            'jumlah_pendaki' => 'required|integer|min:1|max:20',
            'jenis_identitas' => 'required|in:KTP,SIM,KK',
            'no_identitas' => 'required|string|max:30|unique:pendaftaran,no_identitas',
            'foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|integer|min:10|max:80',
            'anggota' => 'nullable|array|max:19', // Max 19 anggota tambahan
            'anggota.*.nama' => 'required|string|max:100',
            'anggota.*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'anggota.*.usia' => 'required|integer|min:10|max:80',
            'anggota.*.no_telepon' => 'required|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'anggota.*.jenis_identitas' => 'required|in:KTP,SIM,KK',
            'anggota.*.no_identitas' => 'required|string|max:30|unique:anggota_pendakian,no_identitas',
            'anggota.*.foto_identitas' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'alat_peminjaman' => 'nullable|array',
            'alat_peminjaman.*' => 'integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rute_pendakian_id.required' => 'Rute pendakian harus dipilih.',
            'rute_pendakian_id.exists' => 'Rute pendakian yang dipilih tidak valid.',
            'tanggal_pendakian.required' => 'Tanggal pendakian harus diisi.',
            'tanggal_pendakian.after_or_equal' => 'Tanggal pendakian tidak boleh sebelum hari ini.',
            'tanggal_turun.after' => 'Tanggal turun harus setelah tanggal pendakian.',
            'jumlah_pendaki.required' => 'Jumlah pendaki harus diisi.',
            'jumlah_pendaki.min' => 'Minimal 1 pendaki.',
            'jumlah_pendaki.max' => 'Maksimal 20 pendaki.',
            'jenis_identitas.required' => 'Jenis identitas harus dipilih.',
            'jenis_identitas.in' => 'Jenis identitas tidak valid.',
            'no_identitas.required' => 'Nomor identitas harus diisi.',
            'no_identitas.unique' => 'Nomor identitas sudah digunakan.',
            'foto_identitas.required' => 'Foto identitas harus diupload.',
            'foto_identitas.image' => 'File harus berupa gambar.',
            'foto_identitas.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'foto_identitas.max' => 'Ukuran gambar maksimal 2MB.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'usia.required' => 'Usia harus diisi.',
            'usia.min' => 'Usia minimal 10 tahun.',
            'usia.max' => 'Usia maksimal 80 tahun.',
            'anggota.array' => 'Data anggota tidak valid.',
            'anggota.max' => 'Maksimal 19 anggota tambahan.',
            'anggota.*.nama.required' => 'Nama anggota harus diisi.',
            'anggota.*.jenis_kelamin.required' => 'Jenis kelamin anggota harus dipilih.',
            'anggota.*.usia.required' => 'Usia anggota harus diisi.',
            'anggota.*.no_telepon.required' => 'Nomor telepon anggota harus diisi.',
            'anggota.*.no_telepon.regex' => 'Format nomor telepon tidak valid.',
            'anggota.*.jenis_identitas.required' => 'Jenis identitas anggota harus dipilih.',
            'anggota.*.no_identitas.required' => 'Nomor identitas anggota harus diisi.',
            'anggota.*.no_identitas.unique' => 'Nomor identitas anggota sudah digunakan.',
            'anggota.*.foto_identitas.required' => 'Foto identitas anggota harus diupload.',
            'anggota.*.foto_identitas.image' => 'File anggota harus berupa gambar.',
            'anggota.*.foto_identitas.mimes' => 'Format gambar anggota harus JPEG, PNG, atau JPG.',
            'anggota.*.foto_identitas.max' => 'Ukuran gambar anggota maksimal 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'rute_pendakian_id' => 'rute pendakian',
            'tanggal_pendakian' => 'tanggal pendakian',
            'tanggal_turun' => 'tanggal turun',
            'jumlah_pendaki' => 'jumlah pendaki',
            'jenis_identitas' => 'jenis identitas',
            'no_identitas' => 'nomor identitas',
            'foto_identitas' => 'foto identitas',
            'jenis_kelamin' => 'jenis kelamin',
            'usia' => 'usia',
            'anggota.*.nama' => 'nama anggota',
            'anggota.*.jenis_kelamin' => 'jenis kelamin anggota',
            'anggota.*.usia' => 'usia anggota',
            'anggota.*.no_telepon' => 'nomor telepon anggota',
            'anggota.*.jenis_identitas' => 'jenis identitas anggota',
            'anggota.*.no_identitas' => 'nomor identitas anggota',
            'anggota.*.foto_identitas' => 'foto identitas anggota',
        ];
    }
}
