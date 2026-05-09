<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@perumahan.com'],
            [
                'name' => 'Admin Perumahan',
                'password' => Hash::make('#asmin201819'),
                'role' => 'admin',
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'warga@perumahan.com'],
            [
                'name' => 'Warga Perumahan',
                'password' => Hash::make('#asmin201819'),
                'role' => 'user',
            ]
        );

        \App\Models\Warga::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => 'Warga Perumahan',
                'blok_rumah' => 'A',
                'nomor_rumah' => '01',
                'no_hp' => '08123456789',
                'status' => 'aktif',
            ]
        );

        $this->call([
            CoaSeeder::class,
            KategoriIuranSeeder::class,
        ]);
    }
}
