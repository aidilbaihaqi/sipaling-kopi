<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiKey::create([
            'api_key' => Str::random(32),
            'app_name' => 'Development App',
            'status' => 'active',
            'permission' => 'development',
        ]);

        ApiKey::create([
            'api_key' => Str::random(32),
            'app_name' => 'Production App',
            'status' => 'active',
            'permission' => 'production',
        ]);

        ApiKey::create([
            'api_key' => Str::random(32),
            'app_name' => 'Testing App',
            'status' => 'active',
            'permission' => 'testing',
        ]);
    }
}
