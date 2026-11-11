<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gunung extends Model
{
    use HasFactory;

    protected $table = 'gunung';
    protected $primaryKey = 'id_gunung';
    protected $fillable = [
        'nama_gunung',
        'lokasi',
        'ketinggian',
        'latitude',
        'longitude',
        'harga_simaksi',
        'deskripsi',
        'gambar',
    ];

    // Relasi ke rute pendakian
    public function rutePendakian()
    {
         return $this->hasMany(\App\Models\RutePendakian::class, 'gunung_id', 'id_gunung');
    }

    // Relasi ke pendaftaran
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_gunung', 'id_gunung');
    }
}
