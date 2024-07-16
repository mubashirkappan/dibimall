<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;

class ConfirmOrderAction
{
    public function execute($shopId)
    {
        $cartItems = Cart::where('shop_id', $shopId)
            ->where('customer_id', auth()->user()->id)
            ->where('purchased',0)
            ->get();
        if ($cartItems->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }        
        $order_name = 'DB_ORDER_'.uniqid();

        $order = Order::create(['order_name' => $order_name, 'is_accepted_by_owner' => 0]);
        $message = "I am ordering some items from your shop. Please check your orders.";

        foreach ($cartItems as $cartItem) {
            $item = Item::find($cartItem->item_id);
            $cartItem->update(['item_id'=>null,'purchased' => 1, 'price' => $item->price,  'dibi_price' => $item->dibi_price,'item_name'=>$item->name,'item_image_name'=>$item->image_name,'shop_id'=>null,'shop_name'=>$item->shop->name]);
            OrderItem::create(['order_id' => $order->id, 'cart_id' => $cartItem->id]);
            $message .= "\n" .$cartItem->item_name . " - Quantity: " . $cartItem->count . ", Price: " . $cartItem->dibi_price;        
        }
        $shop = Shop::find($shopId);
        $number = $shop->country_code.$shop->phone;
        // $customer = Customer::find(auth()->user()->id);
        // $customer->increment('reward_coin', 25);
        //HERE ADDD ITEMS TOOO
        $message = rawurlencode($message);
        $data['link'] = "https://wa.me/$number?text=$message";
        $data['cartItemCountNotPurchased'] = Cart::where('customer_id', auth()->user()->id)->where('purchased', 0)->count();
        return [
            'success' => true,
            'data' => $data,
            'message' => 'order Confirmed successfully.',
        ];
    }
}
