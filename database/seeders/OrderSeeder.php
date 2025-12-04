<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini membuat transaksi dummy untuk:
     * - Testing dashboard (grafik, statistik)
     * - Testing laporan penjualan
     * - Demo sistem ke client
     */
    public function run(): void
    {
        // Ambil user cashier (yang akan jadi pembuat order)
        $cashier = User::where('role', 'cashier')->first();
        
        if (!$cashier) {
            echo "❌ User cashier tidak ditemukan. Jalankan UserSeeder dulu!\n";
            return;
        }

        // Ambil menu yang tersedia
        $menus = Menu::where('is_available', true)->get();
        
        if ($menus->isEmpty()) {
            echo "❌ Menu tidak ditemukan. Jalankan MenuSeeder dulu!\n";
            return;
        }

        echo "🔄 Membuat transaksi dummy...\n";

        // ========================================
        // TRANSAKSI 7 HARI TERAKHIR (untuk grafik)
        // ========================================
        
        $orderData = [
            // 6 hari lalu
            [
                'date' => Carbon::now()->subDays(6),
                'orders' => [
                    ['customer' => 'Budi Santoso', 'items' => [1, 2], 'total' => 125000, 'payment' => 150000, 'method' => 'cash'],
                    ['customer' => 'Siti Aminah', 'items' => [3, 4], 'total' => 125000, 'payment' => 125000, 'method' => 'qris'],
                ]
            ],
            // 5 hari lalu
            [
                'date' => Carbon::now()->subDays(5),
                'orders' => [
                    ['customer' => 'Ahmad Rizki', 'items' => [5, 6], 'total' => 160000, 'payment' => 200000, 'method' => 'cash'],
                    ['customer' => 'Dewi Lestari', 'items' => [1, 3, 5], 'total' => 160000, 'payment' => 160000, 'method' => 'transfer'],
                ]
            ],
            // 4 hari lalu
            [
                'date' => Carbon::now()->subDays(4),
                'orders' => [
                    ['customer' => 'Eko Prasetyo', 'items' => [2, 4], 'total' => 90000, 'payment' => 100000, 'method' => 'cash'],
                    ['customer' => 'Rina Wati', 'items' => [6, 7], 'total' => 90000, 'payment' => 90000, 'method' => 'qris'],
                ]
            ],
            // 3 hari lalu (hari tertinggi)
            [
                'date' => Carbon::now()->subDays(3),
                'orders' => [
                    ['customer' => 'Joko Widodo', 'items' => [1, 2, 3, 4], 'total' => 210000, 'payment' => 250000, 'method' => 'cash'],
                    ['customer' => 'Mega Wati', 'items' => [5, 6, 7], 'total' => 210000, 'payment' => 210000, 'method' => 'transfer'],
                ]
            ],
            // 2 hari lalu
            [
                'date' => Carbon::now()->subDays(2),
                'orders' => [
                    ['customer' => 'Prabowo S', 'items' => [1, 5], 'total' => 190000, 'payment' => 200000, 'method' => 'cash'],
                    ['customer' => 'Anies B', 'items' => [3, 6], 'total' => 190000, 'payment' => 190000, 'method' => 'qris'],
                ]
            ],
            // Kemarin
            [
                'date' => Carbon::now()->subDays(1),
                'orders' => [
                    ['customer' => 'Ganjar P', 'items' => [2, 4, 6], 'total' => 145000, 'payment' => 150000, 'method' => 'cash'],
                    ['customer' => 'Ridwan K', 'items' => [1, 7], 'total' => 145000, 'payment' => 145000, 'method' => 'transfer'],
                ]
            ],
            // Hari ini
            [
                'date' => Carbon::now(),
                'orders' => [
                    ['customer' => 'Sandiaga U', 'items' => [3, 5, 7], 'total' => 175000, 'payment' => 200000, 'method' => 'cash'],
                    ['customer' => 'Erick T', 'items' => [1, 2, 6], 'total' => 175000, 'payment' => 175000, 'method' => 'qris'],
                ]
            ],
        ];

        $totalOrders = 0;

        foreach ($orderData as $dayData) {
            foreach ($dayData['orders'] as $orderInfo) {
                // Buat order
                $order = Order::create([
                    'order_number' => 'ORD-' . time() . rand(1000, 9999),
                    'customer_name' => $orderInfo['customer'],
                    'type' => rand(0, 1) ? 'dine-in' : 'takeaway',
                    'table_no' => rand(0, 1) ? rand(1, 10) : null,
                    'total_amount' => $orderInfo['total'],
                    'payment_amount' => $orderInfo['payment'],
                    'payment_method' => $orderInfo['method'],
                    'status' => 'completed', // Semua completed untuk masuk laporan
                    'user_id' => $cashier->id,
                    'created_at' => $dayData['date'],
                    'updated_at' => $dayData['date'],
                ]);

                // Buat order details (items)
                foreach ($orderInfo['items'] as $menuId) {
                    $menu = $menus->find($menuId);
                    if ($menu) {
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'menu_id' => $menu->id,
                            'quantity' => rand(1, 3),
                            'price' => $menu->price,
                            'created_at' => $dayData['date'],
                            'updated_at' => $dayData['date'],
                        ]);
                    }
                }

                $totalOrders++;
                usleep(100000); // Delay 0.1 detik agar order_number unique
            }
        }

        echo "✅ Berhasil membuat {$totalOrders} transaksi dummy\n";
        echo "📊 Data siap untuk dashboard dan laporan\n";
    }
}
