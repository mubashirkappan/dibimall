<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFromTasRequest extends FormRequest
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
            'phonenumber'=>'required|string',
            'name'=>'required|string|max:255',
            'address'=>'nullable|string|max:255',
            'total_price'=>'required|integer',
            'shop_id'=>'required|exists:shops,id',
            'items'=>'required|array',
            'items.*.name'=>'required|string|max:255',
            'items.*.pricePerItem'=>'required|integer',
            'items.*.quantity'=>'required|integer',
            'items.*.totalPrice'=>'required|integer'
        ];
    }
}
