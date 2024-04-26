<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'max:255',
            'phonenumber' => 'max:255|unique:customers',
            'email' => 'required|email|max:255|unique:customers',
            'password' => 'required|confirmed',
            'method' => 'required|string|in:google,apple,normal',
            'place_id'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered. Please use a different email or login with your existing account',
        ];
    }
}
