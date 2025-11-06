<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $cashier;
    private $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->cashier = User::factory()->create(['role' => 'cashier']);
        $this->apiKey = ApiKey::factory()->create();
    }

    private function addApiKeyHeader(): array
    {
        return ['X-API-KEY' => $this->apiKey->key];
    }

    public function test_index_returns_all_orders()
    {
        Order::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/orders', $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_a_new_order()
    {
        $orderData = [
            'type' => 'dine_in',
            'table_no' => 1,
            'status' => 'PENDING',
            'total_price' => 10000,
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/orders', $orderData, $this->addApiKeyHeader());

        $response->assertStatus(201)
            ->assertJsonPath('type', $orderData['type'])
            ->assertJsonPath('table_no', $orderData['table_no'])
            ->assertJsonPath('status', $orderData['status'])
            ->assertJsonPath('total_price', '10000.00');
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/orders', ['type' => 'invalid_type'], $this->addApiKeyHeader());

        $response->assertStatus(422);
    }

    public function test_show_returns_a_specific_order()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/orders/' . $order->id, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonPath('id', $order->id);
    }

    public function test_update_modifies_an_existing_order()
    {
        $order = Order::factory()->create();
        $updateData = ['status' => 'COMPLETED'];

        $response = $this->actingAs($this->admin)->putJson('/api/v1/orders/' . $order->id, $updateData, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_destroy_removes_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson('/api/v1/orders/' . $order->id, [], $this->addApiKeyHeader());

        $response->assertStatus(204);
    }

    public function test_unauthorized_user_cannot_access_orders()
    {
        $this->getJson('/api/v1/orders', $this->addApiKeyHeader())->assertStatus(401);
    }

    public function test_user_with_cashier_role_can_access_orders()
    {
        $this->actingAs($this->cashier)->getJson('/api/v1/orders', $this->addApiKeyHeader())->assertStatus(200);
    }
}
