<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;

class EditShopAction
{
    public function execute($encrypted_id)
    {
        $shop = Shop::find(decrpyt($encrypted_id));
        if(!$shop){
            throw new Exception("can't find a shop to delete", 1);
        }
        $data['message'] = "successfully delete shop";
        $data['data'] = $shop;
        $data['success'] = true;
        return $data;
        // $shops = Shop::all();

        // return ShopResource::collection($shops);
    }
}
