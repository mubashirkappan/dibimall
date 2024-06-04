<?php

namespace App\Actions\Cart;

use App\Models\Cart;

class ListOrderAction
{
    public function execute($userId)
    {
        $cartItems = Cart::where('customer_id', $userId)->where('purchased', 1)
            ->get();
        if (! $cartItems) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }

        $organizedData = [];
        $total = 0;
        $totalNormal = 0;
        foreach ($cartItems as $cart) {
            if (! isset($total_paid[$cart->shop->name])) {
                $total_paid[$cart->shop->name] = 0;
                $total_paid_in_normal[$cart->shop->name] = 0;
                $total_paid_normal_by_shop[$cart->shop->name] = 0;
            }

            if (! array_key_exists($cart->shop->name, $organizedData)) {
                $organizedData[$cart->shop->name] = [
                    'items' => [],
                    'total' => 0,
                    'totalNormal' => 0,
                ];
            }
            $total_paid[$cart->shop->name] += $cart->item->dibi_price * $cart->count;
            $total_paid_normal_by_shop[$cart->shop->name] += $cart->item->price * $cart->count;
            $total_paid_in_normal[$cart->shop->name] += $cart->item->price * $cart->count;
            $organizedData[$cart->shop->name]['items'][] = [
                'item_id' => $cart->item_id,
                'item_name' => $cart->item->name,
                'image' => $cart->item->image_url,
                'price' => $cart->item->price,
                'dibi_price' => $cart->item->dibi_price,
                'count' => $cart->count,
                'shop_id' => $cart->shop_id,
            ];
            $organizedData[$cart->shop->name]['total'] = $total_paid[$cart->shop->name];
            $organizedData[$cart->shop->name]['totalNormal'] = $total_paid_normal_by_shop[$cart->shop->name];
            $total += $total_paid[$cart->shop->name];
            $totalNormal += $total_paid_in_normal[$cart->shop->name];
        }
        $organizedData = collect($organizedData)->mapWithKeys(function ($value, $key) {
            return [str_replace(' ', '', lcfirst(ucwords($key))) => $value];
        })->toArray();
        $organizedData['overallTotal'] = $total;
        $organizedData['overallTotalNormal'] = $totalNormal;

        return [
            'success' => true,
            'data' => $organizedData,
            'message' => 'order Confirmed list.',
        ];
    }
}
