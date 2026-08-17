<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            // `price` is the struck-through MRP; `dibi_price` is what is actually
            // charged and is exposed to the API as `db_price`.
            'price' => 120,
            'dibi_price' => 100,
            'count' => 10,
            'image_name' => 'item.png',
            'active' => true,
            'offer' => false,
            'allow_note' => true,
            'shop_id' => fn () => Shop::factory(),
            'category_id' => fn (array $attributes) => Category::factory()->state([
                'shop_id' => $attributes['shop_id'],
            ]),
        ];
    }

    /** ListItemAction filters on `count > 0`, so this hides the item from the storefront. */
    public function outOfStock(): static
    {
        return $this->state(fn () => ['count' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}
