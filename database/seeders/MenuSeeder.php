<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $coffeeCategory = Category::where('name', 'Coffee')->first();
        $nonCoffeeCategory = Category::where('name', 'Non-Coffee')->first();
        $foodCategory = Category::where('name', 'Food')->first();

        $menus = [
            [
                'id' => (string) Str::uuid(),
                'category_id' => $coffeeCategory->id,
                'name' => 'Espresso',
                'price' => 15000,
                'is_available' => true,
                'stock' => 100,
            ],
            [
                'id' => (string) Str::uuid(),
                'category_id' => $coffeeCategory->id,
                'name' => 'Latte',
                'price' => 25000,
                'is_available' => true,
                'stock' => 100,
            ],
            [
                'id' => (string) Str::uuid(),
                'category_id' => $nonCoffeeCategory->id,
                'name' => 'Ice Tea',
                'price' => 10000,
                'is_available' => true,
                'stock' => 100,
            ],
            [
                'id' => (string) Str::uuid(),
                'category_id' => $foodCategory->id,
                'name' => 'Croissant',
                'price' => 20000,
                'is_available' => true,
                'stock' => 50,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}