<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendingOrder = Order::where('status', 'PENDING')->first();
        $inProgressOrder = Order::where('status', 'IN_PROGRESS')->first();
        $completedOrder = Order::where('status', 'COMPLETED')->first();

        $espresso = Menu::where('name', 'Espresso')->first();
        $latte = Menu::where('name', 'Latte')->first();
        $iceTea = Menu::where('name', 'Ice Tea')->first();

        $orderItems = [
            [
                'id' => (string) Str::uuid(),
                'order_id' => $pendingOrder->id,
                'menu_id' => $espresso->id,
                'quantity' => 1,
                'status' => 'PENDING',
            ],
            [
                'id' => (string) Str::uuid(),
                'order_id' => $inProgressOrder->id,
                'menu_id' => $latte->id,
                'quantity' => 2,
                'status' => 'IN_PROGRESS',
            ],
            [
                'id' => (string) Str::uuid(),
                'order_id' => $completedOrder->id,
                'menu_id' => $iceTea->id,
                'quantity' => 1,
                'status' => 'COMPLETED',
            ],
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }
    }
}