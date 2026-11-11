<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'user_id',
        'rute_pendakian_id',
        'tanggal_pendakian',
        'tanggal_turun',
        'jumlah_pendaki',
        'jenis_identitas',
        'no_identitas',
        'foto_identitas',
        'status',
        'status_pembayaran',
        'total_biaya',
        'peminjaman_id',
        'biaya_alat',
        'status_peminjaman',
        'status_verifikasi_peminjaman',
    ];

    public function rutePendakian()
    {
        return $this->belongsTo(RutePendakian::class, 'rute_pendakian_id', 'id_rute');
    }

    public function gunung()
    {
        return $this->hasOneThrough(
            Gunung::class,
            RutePendakian::class,
            'id_rute',       // Foreign key di tabel rute_pendakian
            'id_gunung',     // Primary key di tabel gunung
            'rute_pendakian_id', // Foreign key di tabel pendaftaran
            'gunung_id'      // Foreign key di tabel rute_pendakian (ini yang benar)
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Relasi ke AnggotaPendakian (1 pendaftaran punya banyak anggota)
    public function anggotaPendakian()
    {
        return $this->hasMany(AnggotaPendakian::class, 'pendaftaran_id', 'id_pendaftaran');
    }
}
