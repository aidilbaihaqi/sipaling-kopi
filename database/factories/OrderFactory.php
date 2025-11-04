<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['dine_in', 'takeaway']),
            'table_no' => $this->faker->optional()->numberBetween(1, 20),
            'status' => $this->faker->randomElement(['PENDING', 'IN_PROGRESS', 'COMPLETED']),
            'total_price' => $this->faker->numberBetween(20000, 200000),
        ];
    }
}
