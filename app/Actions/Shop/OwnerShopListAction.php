<?php

namespace App\Actions\Shop;

use App\Http\Resources\ShopResource;
use App\Models\Shop;

class OwnerShopListAction
{
    public function execute()
    {

        $shops = Shop::with('Items')->active()->where('customer_id', auth()->user()->id)->get();
        $data = ShopResource::collection($shops);

        return [
            'success' => true,
            'data' => $data,
            'message' => 'shops list',
        ];
    }
}
