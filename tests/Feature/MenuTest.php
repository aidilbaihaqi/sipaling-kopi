<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Menu;
use App\Models\User;
use App\Models\Category;
use App\Models\ApiKey;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $cashier;
    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->cashier = User::factory()->create(['role' => 'cashier']);
        $this->apiKey = ApiKey::factory()->create();
    }

    private function addApiKeyHeader()
    {
        return ['X-API-KEY' => $this->apiKey->key];
    }

    public function test_index_returns_all_menus()
    {
        Menu::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/menus', $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_a_new_menu()
    {
        $category = Category::factory()->create();
        $menuData = [
            'name' => 'New Menu',
            'category_id' => $category->id,
            'price' => 10000,
            'is_available' => true,
            'stock' => 10,
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/menus', $menuData, $this->addApiKeyHeader());

        $response->assertStatus(201)
             ->assertJsonPath('name', $menuData['name'])
             ->assertJsonPath('price', '10000.00');
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/menus', [], $this->addApiKeyHeader());

        $response->assertStatus(422);
    }

    public function test_show_returns_a_specific_menu()
    {
        $menu = Menu::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/menus/' . $menu->id, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $menu->name]);
    }

    public function test_update_modifies_an_existing_menu()
    {
        $menu = Menu::factory()->create();
        $updateData = ['name' => 'Updated Menu'];

        $response = $this->actingAs($this->admin)->putJson('/api/v1/menus/' . $menu->id, $updateData, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_destroy_removes_a_menu()
    {
        $menu = Menu::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson('/api/v1/menus/' . $menu->id, [], $this->addApiKeyHeader());

        $response->assertStatus(204);
    }

    public function test_unauthorized_user_cannot_access_menus()
    {
        $menu = Menu::factory()->create();

        $this->getJson('/api/v1/menus', $this->addApiKeyHeader())->assertStatus(401);
        $this->postJson('/api/v1/menus', ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(401);
        $this->getJson('/api/v1/menus/' . $menu->id, $this->addApiKeyHeader())->assertStatus(401);
        $this->putJson('/api/v1/menus/' . $menu->id, ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(401);
        $this->deleteJson('/api/v1/menus/' . $menu->id, [], $this->addApiKeyHeader())->assertStatus(401);
    }

    public function test_user_with_user_role_cannot_modify_menus()
    {
        $menu = Menu::factory()->create();

        $this->actingAs($this->cashier)->postJson('/api/v1/menus', ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(403);
        $this->actingAs($this->cashier)->putJson('/api/v1/menus/' . $menu->id, ['name' => 'test'], $this->addApiKeyHeader())->assertStatus(403);
        $this->actingAs($this->cashier)->deleteJson('/api/v1/menus/' . $menu->id, [], $this->addApiKeyHeader())->assertStatus(403);
    }
}
