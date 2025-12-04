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
     * 
     * Urutan seeder penting:
     * 1. AdminSeeder - Buat user admin
     * 2. CategorySeeder - Buat kategori menu
     * 3. MenuSeeder - Buat menu (butuh kategori)
     * 4. UserSeeder - Buat user cashier & kitchen
     * 5. OrderSeeder - Buat transaksi dummy (butuh user & menu)
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,      // User admin
            CategorySeeder::class,   // Kategori menu
            MenuSeeder::class,       // Menu items
            UserSeeder::class,       // User cashier & kitchen (BARU)
            OrderSeeder::class,      // Transaksi dummy (BARU)
        ]);
    }
}
