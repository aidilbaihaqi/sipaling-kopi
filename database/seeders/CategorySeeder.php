<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kopi Panas', 'description' => 'Berbagai macam kopi panas'],
            ['name' => 'Kopi Dingin', 'description' => 'Berbagai macam kopi dingin'],
            ['name' => 'Non Kopi', 'description' => 'Minuman non kopi'],
            ['name' => 'Makanan', 'description' => 'Makanan pendamping'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
