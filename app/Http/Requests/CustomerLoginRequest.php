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
            'email' => 'required_without_all:phonenumber,identifier|email|max:255',
            'phonenumber' => 'required_without_all:email,identifier|max:15',
            'identifier' => 'required_if:method,normal|max:255', // Added for normal login method
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
            'email.required_without_all' => 'The email field is required when neither phone number nor identifier is present.',
            'phonenumber.required_without_all' => 'The phone number field is required when neither email nor identifier is present.',
            'identifier.required_if' => 'The identifier field is required for normal login method.',
        ];
    }
}
