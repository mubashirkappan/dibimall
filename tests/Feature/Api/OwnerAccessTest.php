<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * The `is.owner` middleware guarding the owner half of routes/api.php.
 *
 * Only user_type == 2 passes. Registration and /update-to-owner can only reach
 * user_type 3 (pending); an admin grants 2 from the Filament CustomerResource.
 */
class OwnerAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_rejected_from_owner_routes(): void
    {
        $this->getJson('/api/shop/list')->assertUnauthorized();
    }

    public function test_an_approved_owner_can_reach_owner_routes(): void
    {
        $owner = Customer::factory()->owner()->create();
        Shop::factory()->create(['customer_id' => $owner->id, 'name' => 'My Shop']);

        Sanctum::actingAs($owner);

        $this->getJson('/api/shop/list?from=thasweel')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    /**
     * Sharp edge worth knowing before debugging a frontend report: IsOwner denies
     * with redirect()->back(), so an API client gets a redirect, not a 403/JSON.
     */
    public function test_a_plain_buyer_is_denied_with_a_redirect_rather_than_a_403(): void
    {
        Sanctum::actingAs(Customer::factory()->create(['user_type' => 1]));

        $response = $this->getJson('/api/shop/list');

        $response->assertRedirect();
        $this->assertNotSame(403, $response->getStatusCode());
    }

    public function test_a_pending_owner_is_still_denied(): void
    {
        Sanctum::actingAs(Customer::factory()->pendingOwner()->create());

        $this->getJson('/api/shop/list')->assertRedirect();
    }

    /**
     * /update-to-owner only moves 1 -> 3. There is no API route that grants 2,
     * so a buyer cannot self-promote and then create a shop.
     */
    public function test_update_to_owner_only_marks_the_request_as_pending(): void
    {
        $customer = Customer::factory()->create(['user_type' => 1]);

        Sanctum::actingAs($customer);

        $this->getJson('/api/update-to-owner')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(3, $customer->fresh()->user_type);
    }

    public function test_update_to_owner_is_rejected_for_anyone_not_a_plain_buyer(): void
    {
        $owner = Customer::factory()->owner()->create();

        Sanctum::actingAs($owner);

        $this->getJson('/api/update-to-owner')->assertJsonPath('success', false);

        $this->assertSame(2, $owner->fresh()->user_type);
    }

    /**
     * create-shop sits behind is.owner, which already requires user_type 2, so the
     * `user_type => 2` write inside CreateShopAction is a no-op re-write and never
     * a promotion path.
     */
    public function test_a_pending_owner_cannot_create_a_shop(): void
    {
        Sanctum::actingAs(Customer::factory()->pendingOwner()->create());

        $this->postJson('/api/create-shop', ['name' => 'Attempted Shop'])->assertRedirect();

        $this->assertDatabaseCount('shops', 0);
    }
}
