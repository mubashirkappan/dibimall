<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class CustomerLoginAction
{
    public function __invoke($request)
    {
        try {
            $method = $request['method'] ?? null;
            $customer = Customer::where('email', $request['email'])->first();

            if (!$method || !in_array($method, ['google', 'apple', 'normal'])) {
                throw new Exception('Invalid login method');
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
                'email' => $request['email'],
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
                'email' => $request['email'],
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
        if (!Auth::guard('customer')->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            throw new Exception('Invalid Credential');
        }
    }

    private function successResponse($customer)
    {
        $success['token'] = $customer->createToken('MyApp')->plainTextToken;
        $success['id'] = $customer->id;
        $success['email'] = $customer->email;
        $success['username'] = $customer->username;
        return ['success' => true, 'data' => $success, 'message' => 'User logged in successfully.'];
    }
}
