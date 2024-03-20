<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;

class UpdateShopAction
{
    public function execute($request)
    {
        $shop = Shop::find(decrypt($request->id));
        if(!$shop){
            throw new Exception("can't find a shop to delete", 1);
        }else{
            $shop->update($request);
        }
        $data['message'] = "successfully updated shop";
        $data['data'] = $shop;
        $data['success'] = true;
        return $data;
    }
}
