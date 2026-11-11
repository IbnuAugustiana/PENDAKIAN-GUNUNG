<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_telepon',
        'alamat',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Tambahan accessor agar $user->name otomatis ambil dari kolom nama_lengkap
    public function getNameAttribute()
    {
        return $this->nama_lengkap;
    }

    // 🔹 Cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // 🔹 Relasi ke tabel pendaftaran
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'user_id', 'id');
    }
}
