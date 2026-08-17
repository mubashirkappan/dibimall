<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Place>
 */
class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'district' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'India',
            'from' => 'thasweel',
            // The column defaults to false and Place::scopeActive() filters on it,
            // so places must be explicitly activated to appear in shop listings.
            'active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}
