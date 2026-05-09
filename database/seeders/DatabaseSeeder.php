<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin Perumahan',
            'email' => 'admin@perumahan.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'user@perumahan.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        \App\Models\Warga::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Budi Santoso',
            'blok_rumah' => 'A',
            'nomor_rumah' => '01',
            'no_hp' => '08123456789',
            'status' => 'aktif',
        ]);

        $this->call([
            CoaSeeder::class,
            KategoriIuranSeeder::class,
        ]);
    }
}
