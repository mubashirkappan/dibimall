<?php

namespace App\Actions\Shop;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class DeleteShopAction
{
    public function execute($encrypted_id)
    {
        try {
            $shop_id = decrypt($encrypted_id);
            $shop = Shop::find($shop_id);
            if ($shop) {
                $categories = Category::where('shop_id', $shop_id)->get();

                foreach ($categories as $category) {
                    $items = Item::where('category_id', $category->id)->get();

                    foreach ($items as $item) {
                        $carts = Cart::where('item_id', $item->id)->get();
                        foreach ($carts as $cart) {
                            OrderItem::where('cart_id', $cart->id)->delete();
                        }
                        Cart::where('item_id', $item->id)->delete();
                        $item->delete();
                    }
                    $category->delete();
                }
                $items = Item::where('shop_id', $shop_id)->whereNull('category_id')->get();
                foreach ($items as $item) {
                    $carts = Cart::where('item_id', $item->id)->get();
                    foreach ($carts as $cart) {
                        OrderItem::where('cart_id', $cart->id)->delete();
                    }
                    Cart::where('item_id', $item->id)->delete();
                }
                Item::where('shop_id', $shop_id)->whereNull('category_id')->delete();
                $shop->delete();
            }
            $data['message'] = 'successfully delete shop';
            $data['success'] = true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $data['message'] = $th->getMessage();
            $data['success'] = false;
        }

        return $data;
    }
}
