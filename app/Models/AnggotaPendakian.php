<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaPendakian extends Model
{
    use HasFactory;

    protected $table = 'anggota_pendakian';
    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'pendaftaran_id',
        'nama',
        'jenis_kelamin',
        'usia',
        'no_telepon',
        'jenis_identitas',
        'no_identitas',
        'foto_identitas'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id_pendaftaran');
    }
}