<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddToCartAction;
use App\Actions\Cart\GetCartAction;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    // public function addToCart(){
        
    // }
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
}
