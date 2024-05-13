<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class RemoveFromCartAction
{
    public function execute($itemId)
    {
        $cartItem = Cart::where('id', $itemId)
                        ->where('customer_id', auth()->user()->id)
                        ->first();
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Cart item not found or does not belong to the authenticated user.',
            ];
        }
        $cartItem->delete();
        return [
            'success' => true,
            'message' => 'Item removed from cart successfully.',
        ];
    }
}
