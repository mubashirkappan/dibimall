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
            'country_code' => 'required|',
            'phone' => 'required|integer',
            'free_delivery_above' => 'required|integer',
            'email' => 'required|string',
            'logo' => 'required|file',
            'km' => 'nullable|integer',
            'take_away' => 'required|bool',
            'type_id' => 'required|string',
            'place_id' => 'required|string',
            'delivery' => 'required|bool',
        ];
    }
}
