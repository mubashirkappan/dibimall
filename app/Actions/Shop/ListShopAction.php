<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;

class ListShopAction
{
    public function execute()
    {
        $shops = Shop::all();

        return ShopResource::collection($shops);
    }
}
