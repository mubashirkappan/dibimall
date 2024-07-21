<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Order;

class ListOrderAction
{
    public function execute($userId)
    {
        $orders = Order::whereHas('orderItems', function ($query) use ($userId) {
            $query->whereHas('cart', function ($q) use ($userId) {
                $q->where('customer_id', $userId)->where('purchased', 1);
            });
        })->with(['orderItems','orderItems.cart'])->get();

        // $cartItems = Cart::where('customer_id', $userId)->where('purchased', 1)
        //     ->get();
        if ($orders->isEmpty()) {
            return [
                'success' => false,
                'message' => 'It looks like your cart is empty. add items to your cart and start your shopping journey!',

            ];
        }

        $organizedData = [];
        $total = 0;
        $totalNormal = 0;
        foreach ($orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                $cart = $orderItem->cart;
        
                if (! isset($total_paid[$order->order_name])) {
                    $total_paid[$order->order_name] = 0;
                    $total_paid_in_normal[$order->order_name] = 0;
                    $total_paid_normal_by_shop[$order->order_name] = 0;
                }
        
                if (! array_key_exists($order->order_name, $organizedData)) {
                    $organizedData[$order->order_name] = [
                        'items' => [],
                        'total' => 0,
                        'totalNormal' => 0,
                        'date' => null,
                        'shop' => null,
                    ];
                }
        
                $total_paid[$order->order_name] += $cart->dibi_price * $cart->count;
                $total_paid_normal_by_shop[$order->order_name] += $cart->price * $cart->count;
                $total_paid_in_normal[$order->order_name] += $cart->price * $cart->count;
        
                $organizedData[$order->order_name]['items'][] = [
                    'item_name' => $cart->item_name,
                    'image' => $cart->image_url,
                    'price' => $cart->price,
                    'dibi_price' => $cart->dibi_price,
                    'count' => $cart->count,
                    'created_at' => $cart->created_at,
                ];
        
                $organizedData[$order->order_name]['total'] = $total_paid[$order->order_name];
                $organizedData[$order->order_name]['totalNormal'] = $total_paid_normal_by_shop[$order->order_name];
                $organizedData[$order->order_name]['date'] = $order->created_at;
                $organizedData[$order->order_name]['shop'] = $cart->shop_name;
                $total += $total_paid[$order->order_name];
                $totalNormal += $total_paid_in_normal[$order->order_name];
            }
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
