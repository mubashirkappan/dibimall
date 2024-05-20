<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Item;

class AddToCartAction
{
    public function execute($request)
    {
        $item = Item::find($request->item_id);
        $total = ($item->dibi_price * $request->count);
        Cart::updateOrCreate(['customer_id' => auth()->user()->id,
            'item_id' => $request['item_id'],
            'shop_id' => $request['shop_id']],[
            'count' => $request['count'],
            'total_price' => $total]);

        return [
            'success' => true,
            'data' => [],
            'message' => 'item added to cart successfully',
        ];
    }
}
