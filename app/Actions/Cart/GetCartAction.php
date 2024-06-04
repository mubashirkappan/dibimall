<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class GetCartAction
{
    public function execute()
    {
        try {
            // Fetch all carts for the authenticated user
            $carts = Cart::select('item_id', 'count', 'shop_id')
                ->where('purchased', 0)
                ->where('customer_id', Auth::id())
                ->groupBy('item_id', 'count', 'shop_id')
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
                if (! array_key_exists($cart->item->shop->name, $organizedData)) {
                    $organizedData[$cart->item->shop->name] = [];
                }

                $organizedData[$cart->item->shop->name][] = [
                    'item_id' => $cart->item_id,
                    'item_name' => $cart->item->name,
                    'image' => $cart->item->image_url,
                    'price' => $cart->item->price,
                    'dibi_price' => $cart->item->dibi_price,
                    'count' => $cart->count,
                    'shop_id' => $cart->shop_id,
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
