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
            'phonenumber' => 'required|string',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'total_price' => 'required|numeric',
            'shop_id' => 'required|exists:shops,id',
            'delivery_time' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.pricePerItem' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
            'items.*.totalPrice' => 'required|numeric',
            'items.*.item_note' => 'nullable|string|max:255',
            'items.*.preparation_preference' => 'nullable|string|max:255',
            'items.*.unit' => 'nullable|string|max:255',
        ];
    }
}
