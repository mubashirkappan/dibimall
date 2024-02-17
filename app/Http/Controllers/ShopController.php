<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Actions\Shop\ListShopAction;
use App\Http\Resources\ShopResource;

class ShopController extends Controller
{
    public function index(ListShopAction $action)
    {
        return $action->execute();
    }
}
