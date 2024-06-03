<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'user_name' => 'required|string',
            'landmark' => 'required|string',
            'country_code' => 'required|number',
            'phone' => 'required|number',
            'email' => 'required|string',
            'logo' => 'required|file',
            'km' => 'required|integer',
            'take_away' => 'required|bopol',
            'type_id' => 'required|string',
            'place_id' => 'required|string',
            'delivery' => 'required|bool',
        ];
    }
}
