<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'sometimes|exists:items,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'dibi_price' => 'required|numeric',
            'count' => 'required|integer',
            'image_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'shop_id' => 'required|exists:shops,id',
            'active' => 'boolean',
            'offer' => 'boolean',
            'percentage' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
        ];
    }
}
