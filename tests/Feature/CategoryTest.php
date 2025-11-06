<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;
    private ApiKey $apiKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'cashier',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->apiKey = ApiKey::factory()->create();
    }

    private function addApiKeyHeader(array $headers = []): array
    {
        return array_merge($headers, [
            'X-API-KEY' => $this->apiKey->api_key,
        ]);
    }

    public function test_index_returns_all_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/categories', $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_a_new_category()
    {
        $categoryData = [
            'name' => 'New Category',
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/categories', $categoryData, $this->addApiKeyHeader());

        $response->assertStatus(201)
            ->assertJsonFragment($categoryData);

        $this->assertDatabaseHas('categories', $categoryData);
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/categories', ['name' => ''], $this->addApiKeyHeader());

        $response->assertStatus(422);
    }

    public function test_show_returns_a_specific_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/categories/' . $category->id, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $category->name]);
    }

    public function test_update_modifies_an_existing_category()
    {
        $category = Category::factory()->create();
        $updatedData = [
            'name' => 'Updated Category',
        ];

        $response = $this->actingAs($this->admin)->putJson('/api/v1/categories/' . $category->id, $updatedData, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('categories', $updatedData);
    }

    public function test_destroy_removes_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson('/api/v1/categories/' . $category->id, [], $this->addApiKeyHeader());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_unauthorized_user_cannot_access_categories()
    {
        $category = Category::factory()->create();

        $this->getJson('/api/v1/categories', $this->addApiKeyHeader())->assertStatus(401);
        $this->postJson('/api/v1/categories', ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(401);
        $this->getJson('/api/v1/categories/' . $category->id, $this->addApiKeyHeader())->assertStatus(401);
        $this->putJson('/api/v1/categories/' . $category->id, ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(401);
        $this->deleteJson('/api/v1/categories/' . $category->id, [], $this->addApiKeyHeader())->assertStatus(401);
    }

    public function test_user_with_user_role_cannot_modify_categories()
    {
        $category = Category::factory()->create();

        $this->actingAs($this->user)->postJson('/api/v1/categories', ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(403);
        $this->actingAs($this->user)->putJson('/api/v1/categories/' . $category->id, ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(403);
        $this->actingAs($this->user)->deleteJson('/api/v1/categories/' . $category->id, [], $this->addApiKeyHeader())->assertStatus(403);
    }
}