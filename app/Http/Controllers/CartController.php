<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Cart\GetCartAction;
use App\Actions\Cart\AddToCartAction;
use App\Http\Requests\AddToCartRequest;
use App\Actions\Cart\RemoveFromCartAction;

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
        $itemId = $request->input('item_id');
        $response = $action->execute($itemId);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
