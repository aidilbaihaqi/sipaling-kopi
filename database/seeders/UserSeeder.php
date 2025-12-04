<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini membuat user untuk Cashier dan Kitchen
     * Sehingga bisa langsung login dan test sistem
     */
    public function run(): void
    {
        // Cek apakah user sudah ada, jika belum baru dibuat
        
        // 1. User Cashier
        if (!User::where('email', 'kasir@sipalingkopi.com')->exists()) {
            User::create([
                'name' => 'Staff Kasir',
                'email' => 'kasir@sipalingkopi.com',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
            ]);
            echo "✅ User Cashier berhasil dibuat\n";
        }

        // 2. User Kitchen
        if (!User::where('email', 'kitchen@sipalingkopi.com')->exists()) {
            User::create([
                'name' => 'Staff Dapur',
                'email' => 'kitchen@sipalingkopi.com',
                'password' => Hash::make('password123'),
                'role' => 'kitchen',
            ]);
            echo "✅ User Kitchen berhasil dibuat\n";
        }

        // 3. User Cashier 2 (untuk testing multiple cashier)
        if (!User::where('email', 'kasir2@sipalingkopi.com')->exists()) {
            User::create([
                'name' => 'Kasir Siti',
                'email' => 'kasir2@sipalingkopi.com',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
            ]);
            echo "✅ User Cashier 2 berhasil dibuat\n";
        }
    }
}
