<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => (string) Str::uuid(), 'name' => 'Coffee'],
            ['id' => (string) Str::uuid(), 'name' => 'Non-Coffee'],
            ['id' => (string) Str::uuid(), 'name' => 'Food'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}