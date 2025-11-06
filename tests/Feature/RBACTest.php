<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RBACTest extends TestCase
{
    use RefreshDatabase;

    protected $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = ApiKey::factory()->create(['status' => 'active']);
    }

    /**
     * Test admin can access admin-protected route
     */
    public function test_admin_can_access_admin_protected_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->withHeaders([
            'X-API-KEY' => $this->apiKey->api_key,
        ])->getJson('/api/v1/admin/test')
            ->assertStatus(200)
            ->assertJson(['message' => 'Welcome, Admin!']);
    }

    /**
     * Test cashier cannot access admin-protected route
     */
    public function test_cashier_cannot_access_admin_protected_route(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        Sanctum::actingAs($cashier);

        $this->withHeaders([
            'X-API-KEY' => $this->apiKey->api_key,
        ])->getJson('/api/v1/admin/test')
            ->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized. You do not have the required role.']);
    }

    /**
     * Test unauthenticated user cannot access protected route
     */
    public function test_unauthenticated_user_cannot_access_protected_route(): void
    {
        $this->withHeaders([
            'X-API-KEY' => $this->apiKey->api_key,
        ])->getJson('/api/v1/admin/test')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * Test kitchen manager can access kitchen-protected route
     */
    public function test_kitchen_manager_can_access_kitchen_protected_route(): void
    {
        $kitchenManager = User::factory()->create(['role' => 'kitchen_manager']);

        Sanctum::actingAs($kitchenManager);

        $this->withHeaders([
            'X-API-KEY' => $this->apiKey->api_key,
        ])->getJson('/api/v1/kitchen/test')
            ->assertStatus(200)
            ->assertJson(['message' => 'Welcome, Kitchen Manager!']);
    }

    /**
     * Test cashier cannot access kitchen-protected route
     */
    public function test_cashier_cannot_access_kitchen_protected_route(): void
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        Sanctum::actingAs($cashier);

        $this->withHeaders([
            'X-API-KEY' => $this->apiKey->api_key,
        ])->getJson('/api/v1/kitchen/test')
            ->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized. You do not have the required role.']);
    }
}
