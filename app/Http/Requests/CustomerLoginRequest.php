<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'max:255',
            'email' => 'required_without:phonenumber|email|max:255',
            'phonenumber' => 'required_without:email|max:15',
            'password' => 'required|string',
            'method' => 'required|in:google,apple,normal',
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required_without' => 'The email field is required when phone number is not present.',
            'phonenumber.required_without' => 'The phone number field is required when email is not present.',
        ];
    }
}
