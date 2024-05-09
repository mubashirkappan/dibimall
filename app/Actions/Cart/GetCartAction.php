<?php

namespace App\Actions\Cart;

use App\Http\Resources\AllCartResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopResource;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Place;
use App\Models\Shop;

class GetCartAction
{
    public function execute()
    {
// Fetch all carts for the authenticated user
$carts = Cart::where('purchased', 0)
            ->where('customer_id', auth()->user()->id)
            ->get();

// Initialize an empty array to hold the organized data
$organizedData = [];

// Iterate through each cart
foreach ($carts as $cart) {
    // If the shop name is not in the organized data array, create a new key for it
    if (!array_key_exists($cart->shop->name, $organizedData)) {
        $organizedData[$cart->shop->name] = [];
    }
    
    // Add the cart items to the corresponding shop name
    $organizedData[$cart->shop->name][] = $cart->toArray();
}

// Convert the keys (shop names) to camelCase
$organizedData = collect($organizedData)->mapWithKeys(function ($value, $key) {
    return [str_replace(' ', '', lcfirst(ucwords($key)) ) => $value];
})->toArray();

// Return the organized data
// return $organizedData;

        // $data = Cart::select('shop_id')->where('purchased',0)->where('customer_id',auth()->user()->id)->groupBy('shop_id')->get();
        // dd($data);
        // $data = AllCartResource::collection($data);
        // $data = CartResource::collection($data);
            // dd($data);
        
        return [
            'success' => true,
            'data' => $organizedData,
            'message' => 'cart items',
        ];
    }
}
