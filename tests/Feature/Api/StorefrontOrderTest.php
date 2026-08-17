<?php

namespace Tests\Feature\Api;

use App\Models\Item;
use App\Models\Shop;
use App\Models\TasOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * POST /api/order — the storefront checkout path (TasOrder).
 *
 * This is the endpoint the Nuxt storefront posts the whole client-side cart to
 * before handing the buyer off to WhatsApp. It is deliberately unauthenticated.
 */
class StorefrontOrderTest extends TestCase
{
    use RefreshDatabase;

    private function payload(Shop $shop, array $overrides = []): array
    {
        return array_merge([
            'phonenumber' => '9876543210',
            'name' => 'Test Buyer',
            'address' => '12 Market Road',
            'delivery_time' => '2026-08-20 18:00',
            'total_price' => 200,
            'shop_id' => $shop->id,
            'items' => [[
                'name' => 'Chicken Biriyani',
                'pricePerItem' => 100,
                'quantity' => 2,
                'totalPrice' => 200,
                'item_note' => 'less spicy',
                'preparation_preference' => 'separate',
                'unit' => '1 plate',
            ]],
        ], $overrides);
    }

    public function test_guest_can_place_an_order_without_authenticating(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);

        $response = $this->postJson('/api/order', $this->payload($shop));

        $response->assertOk()->assertJsonStructure(['message']);

        $this->assertDatabaseHas('tas_orders', [
            'shop_id' => $shop->id,
            'user_name' => 'Test Buyer',
            'user_phone_number' => '9876543210',
            'status' => 'pending',
        ]);
    }

    public function test_it_persists_per_item_notes_units_and_preferences(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);

        $this->postJson('/api/order', $this->payload($shop))->assertOk();

        $this->assertDatabaseHas('tas_order_items', [
            'tas_order_id' => TasOrder::first()->id,
            'name' => 'Chicken Biriyani',
            'quantity' => 2,
            'unit' => '1 plate',
            'preparation_preference' => 'separate',
            'item_note' => 'less spicy',
        ]);
    }

    public function test_it_decrements_stock_by_matching_item_name_within_the_shop(): void
    {
        $shop = Shop::factory()->create();
        $item = Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);

        $this->postJson('/api/order', $this->payload($shop))->assertOk();

        $this->assertSame(8, $item->fresh()->count);
    }

    /**
     * Stock is matched on (name, shop_id) rather than item id, so an identically
     * named item in another shop must not be touched.
     */
    public function test_it_does_not_decrement_a_same_named_item_belonging_to_another_shop(): void
    {
        $shop = Shop::factory()->create();
        $otherShop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);
        $otherItem = Item::factory()->for($otherShop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);

        $this->postJson('/api/order', $this->payload($shop))->assertOk();

        $this->assertSame(10, $otherItem->fresh()->count);
    }

    /**
     * Documents a known sharp edge: because the decrement matches on name, two
     * items sharing a name inside one shop are both decremented by one order.
     */
    public function test_duplicate_item_names_in_one_shop_are_both_decremented(): void
    {
        $shop = Shop::factory()->create();
        $first = Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);
        $second = Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani', 'count' => 10]);

        $this->postJson('/api/order', $this->payload($shop))->assertOk();

        $this->assertSame(8, $first->fresh()->count);
        $this->assertSame(8, $second->fresh()->count);
    }

    public function test_it_rejects_an_unknown_shop(): void
    {
        $shop = Shop::factory()->create();

        $this->postJson('/api/order', $this->payload($shop, ['shop_id' => 999999]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('shop_id');
    }

    public function test_it_requires_a_name_phone_number_and_at_least_one_item(): void
    {
        $shop = Shop::factory()->create();

        $this->postJson('/api/order', $this->payload($shop, [
            'name' => null,
            'phonenumber' => null,
            'items' => [],
        ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'phonenumber', 'items']);
    }
}
