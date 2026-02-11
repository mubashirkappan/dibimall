<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFromTasRequest;
use App\Models\TasOrder;
use App\Models\TasOrderItem;
use Illuminate\Http\Request;

class TasOrderController extends Controller
{
    public function orderFromTas(OrderFromTasRequest $request)
    {
        $request = $request->validated();
        $order = TasOrder::create([
            'shop_id' => $request['shop_id'],
            'user_name' => $request['name'],
            'user_phone_number' => $request['phonenumber'],
            'delivery_time' => $request['delivery_time'],
            'address' => $request['address'],
            'total_price' => $request['total_price'],
            'created_at' => now(auth()->user()->timezone ?? null),
            'updated_at' => now(auth()->user()->timezone ?? null),
        ]);
        foreach ($request['items'] as $value) {
            TasOrderItem::create([
                'tas_order_id' => $order->id,
                'name' => $value['name'],
                'price_per_item' => $value['pricePerItem'],
                'quantity' => $value['quantity'],
                'totalPrice' => $value['totalPrice'],
                'created_at' => now(auth()->user()->timezone ?? null),
                'updated_at' => now(auth()->user()->timezone ?? null),
            ]);
        }

        return response()->json(['message' => 'Your order has been successfully placed! Thank you for shopping with us.']);
    }

    public function orderList(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);
        $tasOrder = TasOrder::where('shop_id', request('shop_id'))->with(['items'])->get();

        return response()->json(['message' => 'order list', 'data' => $tasOrder]);
    }

    public function statusChange(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:tas_orders,id',
        ]);
        TasOrder::where('id', request('order_id'))->update(['status' => 'deliverd']);

        return response()->json(['message' => 'order status upated']);
    }
}
