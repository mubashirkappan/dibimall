<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Item;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * POST /api/items — the public storefront item list (ListItemAction).
 *
 * Note this is ItemController + App\Actions\Item\, the read-only public path.
 * The owner-facing CRUD lives in ItemsController + App\Actions\Items\.
 */
class ItemListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_active_in_stock_items_for_a_shop(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'In Stock Item']);

        $this->postJson('/api/items', ['shop_id' => $shop->id])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'In Stock Item');
    }

    /**
     * ListItemAction filters on `count > 0`, so an item can disappear from the
     * storefront through stock exhaustion alone, without being deactivated.
     */
    public function test_it_hides_out_of_stock_items(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->outOfStock()->create();

        $this->postJson('/api/items', ['shop_id' => $shop->id])
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_it_hides_inactive_items(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->inactive()->create();

        $this->postJson('/api/items', ['shop_id' => $shop->id])
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_it_never_returns_items_from_another_shop(): void
    {
        $shop = Shop::factory()->create();
        $otherShop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'Mine']);
        Item::factory()->for($otherShop)->create(['name' => 'Theirs']);

        $this->postJson('/api/items', ['shop_id' => $shop->id])
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Mine');
    }

    public function test_it_filters_by_category(): void
    {
        $shop = Shop::factory()->create();
        $drinks = Category::factory()->for($shop)->create(['name' => 'Drinks']);
        $food = Category::factory()->for($shop)->create(['name' => 'Food']);
        Item::factory()->for($shop)->create(['name' => 'Tea', 'category_id' => $drinks->id]);
        Item::factory()->for($shop)->create(['name' => 'Rice', 'category_id' => $food->id]);

        $this->postJson('/api/items', ['shop_id' => $shop->id, 'category_id' => $drinks->id])
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Tea');
    }

    public function test_it_filters_by_keyword_as_a_partial_name_match(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['name' => 'Chicken Biriyani']);
        Item::factory()->for($shop)->create(['name' => 'Mango Juice']);

        $this->postJson('/api/items', ['shop_id' => $shop->id, 'keyword' => 'Biriyani'])
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Chicken Biriyani');
    }

    /**
     * ItemResource renames dibi_price to db_price. db_price is the charged price;
     * `price` is the higher struck-through MRP. Swapping them mis-prices the shop.
     */
    public function test_it_exposes_dibi_price_as_db_price(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['price' => 150, 'dibi_price' => 99]);

        $data = $this->postJson('/api/items', ['shop_id' => $shop->id])->json('data.0');

        $this->assertEquals(99, $data['db_price']);
        $this->assertEquals(150, $data['price']);
        $this->assertArrayNotHasKey('dibi_price', $data);
    }

    public function test_it_exposes_stock_as_available_count_plus_the_note_flag(): void
    {
        $shop = Shop::factory()->create();
        Item::factory()->for($shop)->create(['count' => 7, 'allow_note' => false]);

        $data = $this->postJson('/api/items', ['shop_id' => $shop->id])->json('data.0');

        $this->assertSame(7, $data['available_count']);
        $this->assertFalse($data['allow_note']);
    }

    public function test_it_requires_a_shop_id(): void
    {
        $this->postJson('/api/items', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('shop_id');
    }
}
