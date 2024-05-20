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

            $customer = null;
            if (isset($request['email'])) {
                $customer = Customer::where('email', $request['email'])->first();
            } elseif (isset($request['phonenumber'])) {
                $customer = Customer::where('phonenumber', $request['phonenumber'])->first();
            }

            if (!$customer) {
                throw new Exception('Customer not found');
            }

            switch ($method) {
                case 'google':
                    $this->handleGoogleLogin($customer, $request);
                    break;
                case 'apple':
                    $this->handleAppleLogin($customer, $request);
                    break;
                case 'normal':
                    $this->handleNormalLogin($request);
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
        if (!$customer) {
            $userData = [
                'username' => $request['username'] ?? '',
                'email' => $request['email'] ?? '',
                'phonenumber' => $request['phonenumber'] ?? '',
                'gmail_access_token' => $request['password'],
                'password' => Str::random(16),
            ];
            $customer = Customer::create($userData);
        } elseif (!($customer->gmail_access_token === $request['password'] || $customer->apple_access_token === $request['password'])) {
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
        if (!$customer) {
            $userData = [
                'username' => $request['username'] ?? '',
                'email' => $request['email'] ?? '',
                'phonenumber' => $request['phonenumber'] ?? '',
                'apple_access_token' => $request['password'],
                'password' => Str::random(16),
            ];
            $customer = Customer::create($userData);
        } elseif (!($customer->gmail_access_token === $request['password'] || $customer->apple_access_token === $request['password'])) {
            throw new Exception('Invalid Credential');
        } elseif ($customer->method === 'normal') {
            $customer->update([
                'apple_access_token' => $request['password'],
            ]);
        }

        Auth::login($customer);
    }

    private function handleNormalLogin($request)
    {
        $credentials = ['password' => $request['password']];
        $loginSuccessful = false;

        if (isset($request['email'])) {
            $loginSuccessful = Auth::guard('customer')->attempt(array_merge($credentials, ['email' => $request['email']]));
        }

        if (!$loginSuccessful && isset($request['phonenumber'])) {
            $loginSuccessful = Auth::guard('customer')->attempt(array_merge($credentials, ['phonenumber' => $request['phonenumber']]));
        }

        if (! $loginSuccessful) {
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
