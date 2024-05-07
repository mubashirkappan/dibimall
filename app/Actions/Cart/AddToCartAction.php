<?php

namespace App\Actions\Cart;

use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopResource;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Place;
use App\Models\Shop;

class AddToCartAction
{
    public function execute($request)
    {
        $total = ($request->item_id*$request->count);
        Cart::create(['customer_id'=>auth()->user()->id,
                'item_id'=>$request['item_id'],
                'count'=>$request['count'],
                'shop_id'=>$request['shop_id'],
                'total_price'=>$total]);
        return [
            'success' => true,
            'data' => [],
            'message' => 'item added to cart successfully',
        ];
    }
}
