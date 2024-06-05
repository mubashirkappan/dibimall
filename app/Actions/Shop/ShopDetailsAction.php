<?php

namespace App\Actions\Shop;

use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Place;
use App\Models\Shop;

class ShopDetailsAction
{
    public function execute($username)
    {
        $shops = Shop::with('Items')->active()->where('slug',$username)->first();

        $data =  ShopResource::collection($shops);
        return [
            'success' => true,
            'data' => $data,
            'message' => 'shops list',
        ];
    }
}
