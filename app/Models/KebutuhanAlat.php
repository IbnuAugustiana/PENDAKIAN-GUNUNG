<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanAlat extends Model
{
    use HasFactory;

    protected $table = 'kebutuhan_alat';
    protected $primaryKey = 'id_alat';
    protected $fillable = [
        'id_pendaftaran',
        'nama_alat',
        'jumlah',
        'status',
    ];

    // Relasi ke pendaftaran
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }
}
