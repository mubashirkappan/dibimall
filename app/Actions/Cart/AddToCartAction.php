<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class AddToCartAction
{
    public function execute($request)
    {
        $total = ($request->item_id * $request->count);
        Cart::create(['customer_id' => auth()->user()->id,
            'item_id' => $request['item_id'],
            'count' => $request['count'],
            'shop_id' => $request['shop_id'],
            'total_price' => $total]);

        return [
            'success' => true,
            'data' => [],
            'message' => 'item added to cart successfully',
        ];
    }
}
