<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GunungSeeder::class,
            RutePendakianSeeder::class,
            AnggotaPendakianSeeder::class,
            KebutuhanAlatSeeder::class,
            PembayaranSeeder::class,
        ]);
    }
}
