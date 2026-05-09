<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coas = [
            ['kode_akun' => '101', 'nama_akun' => 'Kas Kecil', 'tipe' => 'aset'],
            ['kode_akun' => '102', 'nama_akun' => 'Bank', 'tipe' => 'aset'],
            ['kode_akun' => '401', 'nama_akun' => 'Pendapatan Iuran Keamanan', 'tipe' => 'pendapatan'],
            ['kode_akun' => '402', 'nama_akun' => 'Pendapatan Iuran Kebersihan', 'tipe' => 'pendapatan'],
            ['kode_akun' => '403', 'nama_akun' => 'Pendapatan Kas Lingkungan', 'tipe' => 'pendapatan'],
            ['kode_akun' => '404', 'nama_akun' => 'Pendapatan Perawatan', 'tipe' => 'pendapatan'],
            ['kode_akun' => '405', 'nama_akun' => 'Pendapatan Lainnya', 'tipe' => 'pendapatan'],
            ['kode_akun' => '501', 'nama_akun' => 'Beban Kebersihan', 'tipe' => 'beban'],
            ['kode_akun' => '502', 'nama_akun' => 'Beban Keamanan', 'tipe' => 'beban'],
            ['kode_akun' => '503', 'nama_akun' => 'Beban Perawatan', 'tipe' => 'beban'],
            ['kode_akun' => '504', 'nama_akun' => 'Beban Lainnya', 'tipe' => 'beban'],
        ];

        foreach ($coas as $coa) {
            \App\Models\Coa::create($coa);
        }
    }
}
