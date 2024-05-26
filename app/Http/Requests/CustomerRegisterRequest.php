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
            'phonenumber' => 'required|max:255|unique:customers',
            'email' => 'nullable|email|max:255|unique:customers',
            'password' => 'required',
            'method' => 'required|string|in:google,apple,normal',
            'is_owner' => 'required|boolean',
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
