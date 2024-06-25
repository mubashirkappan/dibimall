<?php

namespace App\Actions\Shop;

use App\Models\Cart;

class OrdersForShopAction
{
    public function execute($userId)
    {
        $cartItems = Cart::where('purchased', 1)->with(['shop'])->whereHas('shop', function ($q) use ($userId) {
            $q->where('customer_id', $userId);
        })->get();
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
                    // 'totalNormal' => 0,
                ];
            }
            $total_paid[$cart->shop->name] += $cart->dibi_price * $cart->count;
            $total_paid_normal_by_shop[$cart->shop->name] += $cart->price * $cart->count;
            $total_paid_in_normal[$cart->shop->name] += $cart->price * $cart->count;
            $organizedData[$cart->shop->name]['items'][] = [
                'item_id' => $cart->item_id,
                'item_name' => $cart->item->name,
                'image' => $cart->item->image_url,
                'price' => $cart->price,
                'dibi_price' => $cart->dibi_price,
                'count' => $cart->count,
                'customer_name' => $cart->Customer->name,
                'customer_number' => $cart->Customer->phonenumber,
                'order_id' => $cart->order->id,
                'order_name' => $cart->order->order_name,
            ];
            $organizedData[$cart->shop->name]['total'] = $total_paid[$cart->shop->name];
            $organizedData[$cart->shop->name]['totalNormal'] = $total_paid_normal_by_shop[$cart->shop->name];
            $total += $total_paid[$cart->shop->name];
            $totalNormal += $total_paid_in_normal[$cart->shop->name];
        }
        $organizedData = collect($organizedData)->mapWithKeys(function ($value, $key) {
            return [str_replace(' ', '', lcfirst(ucwords($key))) => $value];
        })->toArray();
        // $organizedData['overallTotal'] = $total;
        // $organizedData['overallTotalNormal'] = $totalNormal;

        return [
            'success' => true,
            'data' => $organizedData,
            'message' => 'order Confirmed list.',
        ];
    }
}
