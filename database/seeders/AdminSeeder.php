<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun Admin
        User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@simaksi.com',
            'password' => Hash::make('admin123'),
            'no_telepon' => '081234567890',
            'alamat' => 'Jakarta, Indonesia',
            'role' => 'admin'
        ]);
        
        // Buat akun User contoh
        User::create([
            'nama_lengkap' => 'John Doe',
            'email' => 'user@simaksi.com',
            'password' => Hash::make('user123'),
            'no_telepon' => '081234567891',
            'alamat' => 'Bandung, Indonesia',
            'role' => 'user'
        ]);
        
        echo "✓ Admin dan User berhasil dibuat!\n";
        echo "Admin Email: admin@simaksi.com | Password: admin123\n";
        echo "User Email: user@simaksi.com | Password: user123\n";
    }
}