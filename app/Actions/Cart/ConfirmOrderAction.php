<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;

class ConfirmOrderAction
{
    public function execute($shopId)
    {
        $cartItems = Cart::where('shop_id', $shopId)
            ->where('customer_id', auth()->user()->id)
            ->get();
        if (! $cartItems) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }
        $order_name = 'DB_ORDER_'.time();

        $order = Order::create(['order_name' => $order_name, 'is_accepted_by_owner' => 0]);

        foreach ($cartItems as $cartItem) {
            $cartItem->update(['purchased' => 1]);
            OrderItem::create(['order_id' => $order->id, 'cart_id' => $cartItem->id]);
        }
        $shop = Shop::find($shopId);
        $number = $shop->country_code.$shop->phone;

        $link = "https://wa.me/$number?text=I%20am%20ordering%20some%20items%20from%20your%20shop.%20Please%20check%20your%20orders";

        return [
            'success' => true,
            'data' => $link,
            'message' => 'order Confirmed successfully.',
        ];
    }
}
