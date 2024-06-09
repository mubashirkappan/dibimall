<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Item;
use Exception;

class AddToCartAction
{
    public function execute($request)
    {
        try {
            $item = Item::find($request->item_id);
            if (! $item) {
                throw new Exception('item not fount', 1);
            }
            $total = ($item->dibi_price * $request->count);
            $checkIsPurchased = Cart::where('customer_id', auth()->user()->id)->where('item_id', $request['item_id'])->where('shop_id', $request['shop_id'])->where('purchased', 1)->first();
            if ($checkIsPurchased) {
                Cart::create(['customer_id' => auth()->user()->id,
                    'item_id' => $request['item_id'],
                    'shop_id' => $request['shop_id'],
                    'count' => $request['count'],
                    'total_price' => $total]);
                $message = 'item added to cart successfully';
            } else {
                Cart::updateOrCreate(['customer_id' => auth()->user()->id,
                    'item_id' => $request['item_id'],
                    'shop_id' => $request['shop_id']], [
                        'count' => $request['count'],
                        'total_price' => $total]);
                $message = 'item count updated successfully';

            }
            $data['cartItemCountNotPurchased'] = Cart::where('customer_id', auth()->user()->id)->where('purchased', 0)->count();

            return [
                'success' => true,
                'data' => $data,
                'message' => $message,
            ];
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
