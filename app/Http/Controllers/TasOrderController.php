<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFromTasRequest;
use App\Models\TasOrder;
use App\Models\TasOrderItem;
use Illuminate\Http\Request;

class TasOrderController extends Controller
{
    public function orderFromTas(OrderFromTasRequest $request){
        $request = $request->validated();
        $order = TasOrder::create([
            'shop_id'=>$request['shop_id'],
            'user_name'=>$request['name'],
            'user_phone_number'=>$request['phonenumber'],
            'address'=>$request['address'],
            'total_price'=>$request['total_price'],
        ]);
        foreach ($request['items'] as $value) {
            TasOrderItem::create([
                'tas_order_id'=>$order->id,
                'name'=>$value['name'],
                'price_per_item'=>$value['pricePerItem'],
                'quantity'=>$value['quantity'],
                'totalPrice'=>$value['totalPrice']
            ]);
        }
        return response()->json(['message'=>'Your order has been successfully placed! Thank you for shopping with us.']);
    }
    public function orderList(Request $request){
        $request->validate([
            'shop_id'=>'required|exists:shops,id'
        ]);
        $tasOrder = TasOrder::where('shop_id',request('shop_id'))->with(['items'])->get();
        return response()->json(['message'=>'order list','data'=>$tasOrder]);
    }
}
