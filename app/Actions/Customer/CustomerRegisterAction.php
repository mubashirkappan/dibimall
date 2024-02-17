<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;
use GuzzleHttp\Client;

class CustomerRegisterAction
{
    public function execute(array $validatedData)
    {
        $method = $validatedData['method'];
        unset($validatedData['method']);
        switch ($method) {
            case 'google':
                $validatedData['gmail_access_token'] = $validatedData['password'];
                $validatedData['password'] = Str::random(16);
                break;
            case 'apple':
                $validatedData['apple_access_token'] = $validatedData['password'];
                $validatedData['password'] = Str::random(16);
                break;
            case 'normal':
                $validatedData['password'] = Hash::make($validatedData['password']);
                break;
            default:
                throw new InvalidArgumentException('Invalid registration method');
                break;
        }
        $customer = Customer::create($validatedData);    
        if ($method === 'normal') {
            Auth::login($customer);
            $message = 'Customer registered successfully.';
        } else {
            $message = 'Customer registered and logged in successfully.';
        }
        return $this->SuccessResponse($customer, $method, $message);
    }

    private function SuccessResponse($customer, $method, $message)
    {
        $data = [];

        if ($method !== 'normal') {
            $data = [
                'token' => $customer->createToken('MyApp')->plainTextToken,
                'id' => $customer->id,
                'email' => $customer->email,
                'username' => $customer->username,
                
            ];
        }

        return [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];
    }
}
