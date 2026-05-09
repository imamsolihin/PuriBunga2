<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriIuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Keamanan', 'nominal_default' => 50000],
            ['nama_kategori' => 'Kebersihan', 'nominal_default' => 30000],
            ['nama_kategori' => 'Kas Lingkungan', 'nominal_default' => 20000],
            ['nama_kategori' => 'Perawatan/Fasilitas', 'nominal_default' => 0],
            ['nama_kategori' => 'Lainnya', 'nominal_default' => 0],
        ];

        foreach ($kategori as $kat) {
            \App\Models\KategoriIuran::create($kat);
        }
    }
}
