<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin User
        \App\Models\User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => bcrypt('password123'), 
        ]);

        $this->call([
            BukuSeeder::class,
            AnggotaSeeder::class,
            KategoriSeeder::class,
        ]);
    }
}
