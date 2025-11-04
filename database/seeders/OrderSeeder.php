<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'id' => (string) Str::uuid(),
                'type' => 'dine_in',
                'table_no' => '1',
                'status' => 'PENDING',
                'total_price' => 0,
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'takeaway',
                'table_no' => null,
                'status' => 'PENDING',
                'total_price' => 0,
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'dine_in',
                'table_no' => '2',
                'status' => 'IN_PROGRESS',
                'total_price' => 0,
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'dine_in',
                'table_no' => '3',
                'status' => 'COMPLETED',
                'total_price' => 0,
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}