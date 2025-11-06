<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderItemTest extends TestCase
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

    public function test_index_returns_all_order_items()
    {
        OrderItem::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/order-items', $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_a_new_order_item()
    {
        $order = Order::factory()->create();
        $menu = Menu::factory()->create();

        $orderItemData = [
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price' => 20000,
            'status' => 'PENDING',
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/order-items', $orderItemData, $this->addApiKeyHeader());

        $response->assertStatus(201)
            ->assertJsonFragment($orderItemData);
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/order-items', ['quantity' => 'invalid'], $this->addApiKeyHeader());

        $response->assertStatus(422);
    }

    public function test_show_returns_a_specific_order_item()
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/order-items/' . $orderItem->id, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonPath('id', $orderItem->id);
    }

    public function test_update_modifies_an_existing_order_item()
    {
        $orderItem = OrderItem::factory()->create();
        $updateData = ['status' => 'READY'];

        $response = $this->actingAs($this->admin)->putJson('/api/v1/order-items/' . $orderItem->id, $updateData, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_destroy_removes_an_order_item()
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson('/api/v1/order-items/' . $orderItem->id, [], $this->addApiKeyHeader());

        $response->assertStatus(204);
    }

    public function test_unauthorized_user_cannot_access_order_items()
    {
        $this->getJson('/api/v1/order-items', $this->addApiKeyHeader())->assertStatus(401);
    }

    public function test_user_with_cashier_role_can_access_order_items()
    {
        $this->actingAs($this->cashier)->getJson('/api/v1/order-items', $this->addApiKeyHeader())->assertStatus(200);
    }
}
