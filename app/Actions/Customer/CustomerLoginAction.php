<?php

namespace App\Actions\Customer;

use App\Models\Cart;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerLoginAction
{
    public function __invoke($request)
    {
        try {
            $method = $request['method'] ?? null;

            if (! $method || ! in_array($method, ['google', 'apple', 'normal'])) {
                throw new Exception('Invalid login method');
            }

            $customer = $this->findCustomer($request, $method);

            if (! $customer) {
                throw new Exception('Customer not found');
            }

            switch ($method) {
                case 'google':
                    $this->handleOAuthLogin($customer, $request, 'google');
                    break;
                case 'apple':
                    $this->handleOAuthLogin($customer, $request, 'apple');
                    break;
                case 'normal':
                    $this->handleNormalLogin($customer, $request);
                    break;
                default:
                    throw new Exception('Invalid login method');
            }

            return $this->successResponse($customer);

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function handleGoogleLogin($customer, $request)
    {
        if (! $customer) {
            $userData = [
                'username' => $request['username'] ?? '',
                'email' => $request['email'] ?? '',
                'phonenumber' => $request['phonenumber'] ?? '',
                'gmail_access_token' => $request['password'],
                'password' => Str::random(16),
            ];
            $customer = Customer::create($userData);
        } elseif (! ($customer->gmail_access_token === $request['password'] || $customer->apple_access_token === $request['password'])) {
            throw new Exception('Invalid Credential');
        } elseif ($customer->method === 'normal') {
            $customer->update([
                'gmail_access_token' => $request['password'],
            ]);
        }

        Auth::login($customer);
    }

    private function handleAppleLogin($customer, $request)
    {
        if (! $customer) {
            $userData = [
                'username' => $request['username'] ?? '',
                'email' => $request['email'] ?? '',
                'phonenumber' => $request['phonenumber'] ?? '',
                'apple_access_token' => $request['password'],
                'password' => Str::random(16),
            ];
            $customer = Customer::create($userData);
        } elseif (! ($customer->gmail_access_token === $request['password'] || $customer->apple_access_token === $request['password'])) {
            throw new Exception('Invalid Credential');
        } elseif ($customer->method === 'normal') {
            $customer->update([
                'apple_access_token' => $request['password'],
            ]);
        }

        Auth::login($customer);
    }

    private function findCustomer($request, $method)
    {
        if ($method === 'normal') {
            if (filter_var($request['identifier'], FILTER_VALIDATE_EMAIL)) {
                return Customer::where('email', $request['identifier'])->first();
            } else {
                return Customer::where('phonenumber', $request['identifier'])->first();
            }
        } else {
            if (isset($request['email'])) {
                return Customer::where('email', $request['email'])->first();
            } elseif (isset($request['phonenumber'])) {
                return Customer::where('phonenumber', $request['phonenumber'])->first();
            }
        }

        return null;
    }

    private function handleOAuthLogin($customer, $request, $provider)
    {
        $accessTokenField = $provider.'_access_token';

        if (! $customer->$accessTokenField || $request['password'] !== $customer->$accessTokenField) {
            throw new Exception('Invalid Credential');
        }

        if ($customer->method === 'normal') {
            $customer->update([$accessTokenField => $request['password']]);
        }

        Auth::login($customer);
    }

    private function handleNormalLogin($customer, $request)
    {
        $credentials = [
            'password' => $request['password'],
        ];

        if (filter_var($request['identifier'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request['identifier'];
        } else {
            $credentials['phonenumber'] = $request['identifier'];
        }

        if (! Auth::guard('customer')->attempt($credentials)) {
            throw new Exception('Invalid Credential');
        }
    }

    private function successResponse($customer)
    {
        $owner = ($customer->user_type == 2 ? 1 : 0);
        $item_count = Cart::where('customer_id',$customer->id)->where('purchased',0)->count();
        $success['token'] = $customer->createToken('MyApp')->plainTextToken;
        $success['email'] = $customer->email;
        $success['phonenumber'] = $customer->phonenumber;
        $success['username'] = $customer->username;
        $success['is_owner'] = $owner;
        $success['total_items'] = $item_count;

        return ['success' => true, 'data' => $success, 'message' => 'User logged in successfully.'];
    }
}
