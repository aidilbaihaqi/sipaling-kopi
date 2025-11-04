<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'debit_card']),
            'status' => $this->faker->randomElement(['PENDING', 'PAID', 'FAILED']),
            'status' => $this->faker->randomElement(['PENDING', 'PAID', 'FAILED']),
        ];
    }
}
