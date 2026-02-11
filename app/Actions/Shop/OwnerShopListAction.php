<?php

namespace App\Actions\Shop;

use App\Http\Resources\ShopResource;
use App\Models\Shop;

class OwnerShopListAction
{
    public function execute($shopSlug, $from = null)
    {

        $shops = Shop::with('Items')->active()->where('customer_id', auth()->user()->id)
        ->when($shopSlug, function ($q) use ($shopSlug) {
            $q->where('slug', $shopSlug);
        })
        ->when($from, function ($q) use ($from) {
            $q->where('from', $from);
        })
        ->get();
        $data = ShopResource::collection($shops);

        return [
            'success' => true,
            'data' => $data,
            'message' => 'shops list',
        ];
    }
}
