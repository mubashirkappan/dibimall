<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class GetCartAction
{
    public function execute()
    {
        try {
            $carts = Cart::where('purchased', 0)
                ->where('customer_id', Auth::id())
                ->with('item')
                ->get();

            if ($carts->isEmpty()) {
                return [
                    'success' => true,
                    'data' => [],
                    'message' => 'Oops! No items in the cart.',
                ];
            }

            $organizedData = [];
            foreach ($carts as $cart) {
                if (! array_key_exists($cart->shop->name, $organizedData)) {
                    $organizedData[$cart->shop->name] = [];
                }

                $organizedData[$cart->shop->name][] = [
                    'item_id' => $cart->item_id,
                    'item_name' => $cart->item->name,
                    'image' => $cart->item->image_url,
                ];
            }

            $organizedData = collect($organizedData)->mapWithKeys(function ($value, $key) {
                return [str_replace(' ', '', lcfirst(ucwords($key))) => $value];
            })->toArray();

            return [
                'success' => true,
                'data' => $organizedData,
                'message' => 'Cart Items Retrieved Successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Error occurred while fetching cart items: '.$e->getMessage(),
            ];
        }
    }
}
