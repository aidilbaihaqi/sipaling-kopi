<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['category_id' => 1, 'name' => 'Espresso', 'description' => 'Kopi hitam pekat', 'price' => 15000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Americano', 'description' => 'Espresso dengan air panas', 'price' => 18000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Cappuccino', 'description' => 'Espresso dengan susu foam', 'price' => 22000, 'stock' => 80],
            ['category_id' => 1, 'name' => 'Latte', 'description' => 'Espresso dengan susu', 'price' => 25000, 'stock' => 90],
            ['category_id' => 2, 'name' => 'Es Kopi Susu', 'description' => 'Kopi susu dingin', 'price' => 20000, 'stock' => 120],
            ['category_id' => 2, 'name' => 'Ice Latte', 'description' => 'Latte dingin', 'price' => 28000, 'stock' => 85],
            ['category_id' => 2, 'name' => 'Cold Brew', 'description' => 'Kopi seduh dingin', 'price' => 30000, 'stock' => 60],
            ['category_id' => 3, 'name' => 'Chocolate', 'description' => 'Coklat panas/dingin', 'price' => 22000, 'stock' => 70],
            ['category_id' => 3, 'name' => 'Matcha Latte', 'description' => 'Teh hijau dengan susu', 'price' => 25000, 'stock' => 50],
            ['category_id' => 4, 'name' => 'Croissant', 'description' => 'Roti croissant', 'price' => 18000, 'stock' => 30],
            ['category_id' => 4, 'name' => 'Sandwich', 'description' => 'Sandwich isi ayam', 'price' => 28000, 'stock' => 25],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::create($menu);
        }
    }
}
