<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CustomerRegisterAction
{
    public function execute(array $validatedData)
    {
        $method = $validatedData['method'];
        $is_owner = $validatedData['is_owner'];
        unset($validatedData['method']);
        unset($validatedData['is_owner']);
        if ($is_owner) {
            $validatedData['user_type'] = 3; //pending
        } else {
            $validatedData['user_type'] = 1;
        }

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
        $randomString = Str::random(6); // Adjust the length as needed
        $referralCode = 'dbmall'.$randomString;
        if (isset($validatedData['reffered_by'])) {
            $refered_user = Customer::where('referal_code', $validatedData['reffered_by'])->first();
            $refered_user->increment('reward_coin', 100);
            $validatedData['reffered_by'] = $refered_user->id;
        }
        $validatedData['referal_code'] = $referralCode;
        $validatedData['reward_coin'] = 50;
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
