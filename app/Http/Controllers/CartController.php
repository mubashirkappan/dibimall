<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\ConfirmOrderAction;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\ListOrderAction;
use App\Actions\Cart\RemoveFromCartAction;
use App\Actions\Shop\OrdersForShopAction;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\ListItemRequest;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    public function addToCart(AddToCartRequest $request, AddToCartAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function getCart(GetCartAction $action)
    {
        $response = $action->execute();
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function removeFromCart(Request $request, RemoveFromCartAction $action)
    {
        $request->validate([
            'item_id' => 'required',
        ]);
        $itemId = $request->input('item_id');
        $response = $action->execute($itemId);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function confirmOrder(ListItemRequest $request, ConfirmOrderAction $action)
    {
        $shopId = $request->input('shop_id');
        $response = $action->execute($shopId);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function listCompleteOrders(ListOrderAction $action)
    {
        $response = $action->execute(auth()->user()->id);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function ordersForShop(OrdersForShopAction $action)
    {
        $response = $action->execute(auth()->user()->id);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
