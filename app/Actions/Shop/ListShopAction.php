<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;

class ListShopAction
{
    public function execute()
    {
        $shops = Shop::all();
        $data = ShopResource::collection($shops);
        return [
            'success' => true,
            'data' => $data,
            'message' => 'shops list',
        ];
    }
}
