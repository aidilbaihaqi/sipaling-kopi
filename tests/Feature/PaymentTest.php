<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\ApiKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
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

    public function test_index_returns_all_payments()
    {
        Payment::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/payments', $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_store_creates_a_new_payment()
    {
        $order = Order::factory()->create();

        $paymentData = [
            'order_id' => $order->id,
            'payment_method' => 'cash',
            'status' => 'PENDING',
            'amount' => 10000,
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/v1/payments', $paymentData, $this->addApiKeyHeader());

        $response->assertStatus(201)
            ->assertJsonPath('order_id', $paymentData['order_id'])
            ->assertJsonPath('payment_method', $paymentData['payment_method'])
            ->assertJsonPath('status', $paymentData['status'])
            ->assertJsonPath('amount', '10000.00');
    }

    public function test_store_fails_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->postJson('/api/v1/payments', ['payment_method' => 'invalid'], $this->addApiKeyHeader());

        $response->assertStatus(422);
    }

    public function test_show_returns_a_specific_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($this->admin)->getJson('/api/v1/payments/' . $payment->id, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonPath('id', $payment->id);
    }

    public function test_update_modifies_an_existing_payment()
    {
        $payment = Payment::factory()->create();
        $updateData = ['status' => 'PAID'];

        $response = $this->actingAs($this->admin)->putJson('/api/v1/payments/' . $payment->id, $updateData, $this->addApiKeyHeader());

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);
    }

    public function test_destroy_removes_a_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson('/api/v1/payments/' . $payment->id, [], $this->addApiKeyHeader());

        $response->assertStatus(204);
    }

    public function test_unauthorized_user_cannot_access_payments()
    {
        $this->getJson('/api/v1/payments', $this->addApiKeyHeader())->assertStatus(401);
    }

    public function test_user_with_cashier_role_can_access_payments()
    {
        $this->actingAs($this->cashier)->getJson('/api/v1/payments', $this->addApiKeyHeader())->assertStatus(200);
    }
}
