<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'category_id' => Category::factory(),
            'price' => $this->faker->numberBetween(10000, 50000),
            'is_available' => $this->faker->boolean,
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
