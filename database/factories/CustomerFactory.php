<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            // Customer::setPasswordAttribute() hashes this on assignment.
            'password' => 'password',
            'phonenumber' => fake()->unique()->numerify('9#########'),
            'user_type' => 1,
            'shop_count' => 1,
            'reward_coin' => 0,
            'from' => 'thasweel',
        ];
    }

    /** An approved shop owner: the only user_type the `is.owner` middleware lets through. */
    public function owner(): static
    {
        return $this->state(fn () => ['user_type' => 2]);
    }

    /** Requested ownership, awaiting admin approval in the Filament CustomerResource. */
    public function pendingOwner(): static
    {
        return $this->state(fn () => ['user_type' => 3]);
    }
}
