<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Place;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * GET /api/shops — the public shop listing (ListShopAction).
 *
 * The storefront calls this with ?shop=<slug>&from=thasweel to resolve a single
 * storefront, and the marketplace home calls it with ?city= to browse.
 */
class ShopListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_active_shops(): void
    {
        $shop = Shop::factory()->create(['name' => 'Visible Shop']);

        $this->getJson('/api/shops')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.name', 'Visible Shop')
            ->assertJsonCount(1, 'data');

        $this->assertSame($shop->slug, $this->getJson('/api/shops')->json('data.0.slug'));
    }

    public function test_it_hides_inactive_shops(): void
    {
        Shop::factory()->inactive()->create();

        $this->getJson('/api/shops')->assertOk()->assertJsonCount(0, 'data');
    }

    /**
     * `from` is the tenant discriminator. Dropping it from a query leaks other
     * tenants' shops, which is why every storefront call passes it.
     */
    public function test_it_scopes_results_to_the_requested_tenant(): void
    {
        Shop::factory()->create(['name' => 'Thasweel Shop', 'from' => 'thasweel']);
        Shop::factory()->create(['name' => 'Dibimall Shop', 'from' => 'dibimall']);

        $this->getJson('/api/shops?from=thasweel')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Thasweel Shop');

        $this->getJson('/api/shops')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_it_resolves_a_single_storefront_by_slug(): void
    {
        Shop::factory()->create(['slug' => 'wanted-shop']);
        Shop::factory()->create(['slug' => 'other-shop']);

        $this->getJson('/api/shops?shop=wanted-shop')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'wanted-shop');
    }

    public function test_it_filters_by_city_through_the_places_table(): void
    {
        $kochi = Place::factory()->create(['name' => 'Kochi']);
        $calicut = Place::factory()->create(['name' => 'Calicut']);
        Shop::factory()->create(['name' => 'Kochi Shop', 'place_id' => $kochi->id]);
        Shop::factory()->create(['name' => 'Calicut Shop', 'place_id' => $calicut->id]);

        $this->getJson('/api/shops?city=Kochi')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Kochi Shop');
    }

    /**
     * Place::scopeActive() means a shop attached to an inactive place drops out
     * of every listing, even though the shop itself is active.
     */
    public function test_shops_in_an_inactive_place_are_excluded(): void
    {
        $place = Place::factory()->inactive()->create();
        Shop::factory()->create(['place_id' => $place->id]);

        $this->getJson('/api/shops')->assertOk()->assertJsonCount(0, 'data');
    }

    /** An authenticated owner never sees their own shop in the buyer listing. */
    public function test_an_authenticated_customer_does_not_see_their_own_shops(): void
    {
        $owner = Customer::factory()->owner()->create();
        Shop::factory()->create(['name' => 'My Own Shop', 'customer_id' => $owner->id]);
        Shop::factory()->create(['name' => 'Someone Elses Shop']);

        Sanctum::actingAs($owner);

        $this->getJson('/api/shops')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Someone Elses Shop');
    }

    public function test_the_shop_payload_exposes_an_encrypted_id_not_the_raw_id(): void
    {
        $shop = Shop::factory()->create();

        $data = $this->getJson('/api/shops')->json('data.0');

        $this->assertArrayHasKey('encrypt_id', $data);
        $this->assertNotSame((string) $shop->id, $data['encrypt_id']);
        $this->assertSame($shop->id, decrypt($data['encrypt_id']));
    }
}
