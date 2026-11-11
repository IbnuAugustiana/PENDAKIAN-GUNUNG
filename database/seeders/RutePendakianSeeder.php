<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RutePendakian;

class RutePendakianSeeder extends Seeder
{
    public function run(): void
    {
        $rutes = [
            // Gunung Semeru (id: 1)
            ['gunung_id' => 1, 'nama_rute' => 'Ranu Pani', 'deskripsi' => 'Rute utama melalui Ranu Pani menuju Mahameru', 'harga' => 200000],
            ['gunung_id' => 1, 'nama_rute' => 'Senduro', 'deskripsi' => 'Rute alternatif dari Lumajang', 'harga' => 180000],

            // Gunung Rinjani (id: 2)
            ['gunung_id' => 2, 'nama_rute' => 'Sembalun', 'deskripsi' => 'Rute utama dari Sembalun Lawang', 'harga' => 250000],
            ['gunung_id' => 2, 'nama_rute' => 'Senaru', 'deskripsi' => 'Rute dari Senaru dengan air terjun', 'harga' => 230000],

            // Gunung Bromo (id: 3)
            ['gunung_id' => 3, 'nama_rute' => 'Cemoro Lawang', 'deskripsi' => 'Rute utama menuju lautan pasir', 'harga' => 150000],
            ['gunung_id' => 3, 'nama_rute' => 'Tosari', 'deskripsi' => 'Rute alternatif dari Tosari', 'harga' => 140000],

            // Gunung Merapi (id: 4)
            ['gunung_id' => 4, 'nama_rute' => 'Kaliurang', 'deskripsi' => 'Rute klasik dari Yogyakarta', 'harga' => 120000],
            ['gunung_id' => 4, 'nama_rute' => 'Selo', 'deskripsi' => 'Rute dari Boyolali', 'harga' => 130000],

            // Gunung Merbabu (id: 5)
            ['gunung_id' => 5, 'nama_rute' => 'Selo', 'deskripsi' => 'Rute utama melalui Selo', 'harga' => 140000],
            ['gunung_id' => 5, 'nama_rute' => 'Suwanting', 'deskripsi' => 'Rute dengan view Merapi', 'harga' => 160000],

            // Gunung Sindoro (id: 6)
            ['gunung_id' => 6, 'nama_rute' => 'Kledung', 'deskripsi' => 'Jalur utama Sindoro', 'harga' => 120000],
            ['gunung_id' => 6, 'nama_rute' => 'Sigedang', 'deskripsi' => 'Rute alternatif dari Sigedang', 'harga' => 130000],

            // Gunung Sumbing (id: 7)
            ['gunung_id' => 7, 'nama_rute' => 'Garung', 'deskripsi' => 'Jalur utama Sumbing', 'harga' => 130000],
            ['gunung_id' => 7, 'nama_rute' => 'Cebolak', 'deskripsi' => 'Rute dari Cebolak', 'harga' => 140000],

            // Gunung Lawu (id: 8)
            ['gunung_id' => 8, 'nama_rute' => 'Cemoro Sewu', 'deskripsi' => 'Rute terpopuler Lawu', 'harga' => 125000],
            ['gunung_id' => 8, 'nama_rute' => 'Cemoro Kandang', 'deskripsi' => 'Rute alternatif Cemoro Kandang', 'harga' => 135000],

            // Gunung Prau (id: 9)
            ['gunung_id' => 9, 'nama_rute' => 'Patak Banteng', 'deskripsi' => 'Sunrise terbaik di Dieng', 'harga' => 100000],
            ['gunung_id' => 9, 'nama_rute' => 'Dieng', 'deskripsi' => 'Rute utama dari Dieng', 'harga' => 110000],

            // Gunung Kerinci (id: 10)
            ['gunung_id' => 10, 'nama_rute' => 'Bengkulu', 'deskripsi' => 'Rute dari Bengkulu', 'harga' => 300000],
            ['gunung_id' => 10, 'nama_rute' => 'Solok', 'deskripsi' => 'Rute dari Solok', 'harga' => 280000],

            // Gunung Ciremai (id: 11)
            ['gunung_id' => 11, 'nama_rute' => 'Linggarjati', 'deskripsi' => 'Rute curam tapi indah', 'harga' => 170000],
            ['gunung_id' => 11, 'nama_rute' => 'Apuy', 'deskripsi' => 'Rute alternatif Apuy', 'harga' => 160000],

            // Gunung Arjuno (id: 12)
            ['gunung_id' => 12, 'nama_rute' => 'Tretes', 'deskripsi' => 'Rute Arjuno-Welirang populer', 'harga' => 140000],
            ['gunung_id' => 12, 'nama_rute' => 'Lawang', 'deskripsi' => 'Rute dari Lawang', 'harga' => 135000],

            // Gunung Papandayan (id: 13)
            ['gunung_id' => 13, 'nama_rute' => 'Cilebak', 'deskripsi' => 'Rute utama Papandayan', 'harga' => 130000],
            ['gunung_id' => 13, 'nama_rute' => 'Sari Ater', 'deskripsi' => 'Rute alternatif Sari Ater', 'harga' => 125000],
        ];

        foreach ($rutes as $rute) {
            RutePendakian::create($rute);
        }
    }
}
