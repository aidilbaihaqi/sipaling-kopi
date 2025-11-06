<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_access_the_admin_route()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/v1/admin/test');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Welcome, Admin!']);
    }

    /** @test */
    public function a_non_admin_cannot_access_the_admin_route()
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        Sanctum::actingAs($cashier);

        $response = $this->getJson('/api/v1/admin/test');

        $response->assertStatus(403);
    }

    /** @test */
    public function a_kitchen_manager_can_access_the_kitchen_route()
    {
        $kitchenManager = User::factory()->create(['role' => 'kitchen_manager']);

        Sanctum::actingAs($kitchenManager);

        $response = $this->getJson('/api/v1/kitchen/test');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Welcome, Kitchen Manager!']);
    }

    /** @test */
    public function a_cashier_can_access_the_cashier_route()
    {
        $cashier = User::factory()->create(['role' => 'cashier']);

        Sanctum::actingAs($cashier);

        $response = $this->getJson('/api/v1/cashier/test');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Welcome, Cashier!']);
    }

    /** @test */
    public function a_guest_cannot_access_any_protected_route()
    {
        $response = $this->getJson('/api/v1/admin/test');

        $response->assertStatus(401);
    }
}