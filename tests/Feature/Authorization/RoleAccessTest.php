<?php

namespace Tests\Feature\Authorization;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_collector_cannot_access_admin_dashboard(): void
    {
        $collector = User::factory()->collector()->create();

        $response = $this->actingAs($collector)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_customer_cannot_access_collector_dashboard(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->get('/collector/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_guest_is_redirected_to_login_when_accessing_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_view_another_customers_bill(): void
    {
        $userA = User::factory()->customer()->create();
        $customerA = Customer::factory()->create(['user_id' => $userA->id]);
        $bill = Bill::factory()->create(['customer_id' => $customerA->id]);

        $userB = User::factory()->customer()->create();
        Customer::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userB)->get("/customer/bills/{$bill->id}");

        $response->assertForbidden();
    }

    public function test_customer_can_view_their_own_bill(): void
    {
        $user = User::factory()->customer()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $bill = Bill::factory()->create(['customer_id' => $customer->id]);

        $response = $this->actingAs($user)->get("/customer/bills/{$bill->id}");

        $response->assertOk();
    }
}
