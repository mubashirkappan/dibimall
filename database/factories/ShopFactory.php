<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Place;
use App\Models\Shop;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            // `slug` is what the API and the storefront URL call `user_name`.
            'slug' => fake()->unique()->slug(2),
            'address' => fake()->address(),
            'landmark' => fake()->streetName(),
            'country_code' => '91',
            'phone' => fake()->numerify('9#########'),
            'email' => fake()->unique()->safeEmail(),
            'logo_name' => 'logo.png',
            'image_count' => 1,
            'delivery' => true,
            'km' => 5,
            'take_away' => true,
            'top_shop' => false,
            'active' => 1,
            'item_count' => 50,
            'currency' => 'INR',
            // Tenant discriminator. Storefront queries filter on it.
            'from' => 'thasweel',
            'courier_charge_extra' => false,
            'type_id' => fn () => Type::factory(),
            'place_id' => fn () => Place::factory(),
            'customer_id' => fn () => Customer::factory()->owner(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['active' => 0]);
    }
}
