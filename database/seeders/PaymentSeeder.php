<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $completedOrder = Order::where('status', 'COMPLETED')->first();

        if ($completedOrder) {
            Payment::create([
                'id' => (string) Str::uuid(),
                'order_id' => $completedOrder->id,
                'amount' => 35000,
                'payment_method' => 'CASH',
                'status' => 'PAID',
                'requestId' => (string) Str::uuid(),
            ]);
        }
    }
}