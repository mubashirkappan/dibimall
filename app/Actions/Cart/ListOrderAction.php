<?php

namespace App\Actions\Cart;


use App\Http\Resources\ListConfirmOrderResource;
use App\Models\OrderItem;
use App\Models\Shop;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\Order;

class ListOrderAction
{
    public function execute($userId)
    {
        $cartItems = Cart::where('customer_id',$userId)->where('purchased',1)
            ->get();
            if (! $cartItems) {
                return [
                    'success' => false,
                    'message' => 'Cart item not found.',
                ];
            }     
        $resource = ListConfirmOrderResource::collection($cartItems);   
        return [
            'success' => true,
            'data'=> $resource,
            'message' => 'order Confirmed list.',
        ];
    }
}
