<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutePendakian extends Model
{
    use HasFactory;

    protected $table = 'rute_pendakian';
    protected $primaryKey = 'id_rute';
    protected $fillable = ['gunung_id', 'nama_rute', 'deskripsi', 'harga'];

    // Relasi ke Gunung
    public function gunung()
    {
        return $this->belongsTo(Gunung::class, 'gunung_id', 'id_gunung');
    }

    // Relasi ke Pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'rute_pendakian_id', 'id_rute');
    }

}

