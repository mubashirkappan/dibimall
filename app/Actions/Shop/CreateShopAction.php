<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;

class CreateShopAction
{
    public function execute($request)
    {
        $shop = Shop::create([$request]);
        if(!$shop){
            throw new Exception("something went wrong at shop create", 1);
        }
        $data['message'] = "successfully added a shop";
        $data['data'] = $shop;
        $data['success'] = true;
        return $data;
    }
}
