<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * POST /api/customer-register and /api/customer-login.
 *
 * Customer is the identity behind every API route; User exists only for the
 * Filament admin panel.
 */
class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    private function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test Buyer',
            'username' => 'testbuyer',
            'email' => 'buyer@example.com',
            'phonenumber' => '9876543210',
            'password' => 'password',
            'method' => 'normal',
            'is_owner' => false,
        ], $overrides);
    }

    public function test_registering_without_is_owner_creates_a_plain_buyer(): void
    {
        $this->postJson('/api/customer-register', $this->registrationPayload())
            ->assertCreated();

        $this->assertSame(1, Customer::first()->user_type);
    }

    /** Registering as an owner lands on user_type 3 (pending), never 2. */
    public function test_registering_as_an_owner_creates_a_pending_request(): void
    {
        $this->postJson('/api/customer-register', $this->registrationPayload(['is_owner' => true]))
            ->assertCreated();

        $this->assertSame(3, Customer::first()->user_type);
    }

    public function test_it_hashes_the_password_on_registration(): void
    {
        $this->postJson('/api/customer-register', $this->registrationPayload())->assertCreated();

        $customer = Customer::first();
        $this->assertNotSame('password', $customer->password);
        $this->assertTrue(Hash::check('password', $customer->password));
    }

    public function test_a_referral_code_is_generated_when_none_is_supplied(): void
    {
        $this->postJson('/api/customer-register', $this->registrationPayload())->assertCreated();

        $this->assertNotEmpty(Customer::first()->referal_code);
    }

    public function test_it_rejects_a_duplicate_phone_number(): void
    {
        Customer::factory()->create(['phonenumber' => '9876543210']);

        $this->postJson('/api/customer-register', $this->registrationPayload())
            ->assertStatus(422)
            ->assertJsonValidationErrors('phonenumber');
    }

    /** Normal login matches on `username` only, despite the field being called `identifier`. */
    public function test_login_returns_a_token_and_the_owner_flag(): void
    {
        Customer::factory()->create(['username' => 'shopowner', 'user_type' => 2]);

        $response = $this->postJson('/api/customer-login', [
            'identifier' => 'shopowner',
            'phonenumber' => '9876543210',
            'password' => 'password',
            'method' => 'normal',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.is_owner', 1)
            ->assertJsonStructure(['data' => ['token', 'username', 'is_owner', 'total_items']]);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_a_plain_buyer_logs_in_with_is_owner_zero(): void
    {
        Customer::factory()->create(['username' => 'buyer', 'user_type' => 1]);

        $this->postJson('/api/customer-login', [
            'identifier' => 'buyer',
            'phonenumber' => '9876543210',
            'password' => 'password',
            'method' => 'normal',
        ])->assertOk()->assertJsonPath('data.is_owner', 0);
    }

    /**
     * Action-layer failures surface as HTTP 404 with success:false, because
     * BaseController::sendError() defaults to 404 rather than 401/422.
     */
    public function test_a_wrong_password_fails_with_success_false(): void
    {
        Customer::factory()->create(['username' => 'buyer']);

        $this->postJson('/api/customer-login', [
            'identifier' => 'buyer',
            'phonenumber' => '9876543210',
            'password' => 'wrong-password',
            'method' => 'normal',
        ])
            ->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    public function test_get_user_returns_the_authenticated_customer(): void
    {
        $customer = Customer::factory()->create(['name' => 'Test Buyer', 'user_type' => 2]);

        Sanctum::actingAs($customer);

        $this->getJson('/api/get-user')
            ->assertOk()
            ->assertJsonPath('data.name', 'Test Buyer')
            ->assertJsonPath('data.is_owner', 1);
    }

    public function test_get_user_requires_authentication(): void
    {
        $this->getJson('/api/get-user')->assertUnauthorized();
    }
}
