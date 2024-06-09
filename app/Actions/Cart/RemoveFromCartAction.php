<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class RemoveFromCartAction
{
    public function execute($itemId)
    {
        $cartItem = Cart::where('item_id', $itemId)
            ->where('customer_id', auth()->user()->id)
            ->where('purchased', 0)
            ->first();
        if (! $cartItem) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }
        $cartItem->delete();
        $data['cartItemCountNotPurchased'] = Cart::where('customer_id', auth()->user()->id)->where('purchased', 0)->count();

        return [
            'success' => true,
            'data' => $data,
            'message' => 'Item removed from cart successfully.',
        ];
    }
}
