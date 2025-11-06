<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiKeyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_an_error_if_api_key_is_missing()
    {
        $response = $this->getJson('/api/v1/test-api-key');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'API key is missing.',
            ]);
    }

    /** @test */
    public function it_returns_an_error_if_api_key_is_invalid()
    {
        $response = $this->withHeaders([
            'X-API-KEY' => 'invalid-api-key',
        ])->getJson('/api/v1/test-api-key');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid API key.',
            ]);
    }

    /** @test */
    public function it_returns_an_error_if_api_key_is_inactive()
    {
        $apiKey = ApiKey::factory()->create([
            'status' => 'inactive',
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $apiKey->api_key,
        ])->getJson('/api/v1/test-api-key');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'API key is inactive.',
            ]);
    }

    /** @test */
    public function it_allows_access_with_valid_api_key()
    {
        $apiKey = ApiKey::factory()->create();

        $response = $this->withHeaders([
            'X-API-KEY' => $apiKey->api_key,
        ])->getJson('/api/v1/test-api-key');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'API key is valid',
            ]);
    }
}