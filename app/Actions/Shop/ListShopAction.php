<?php

namespace App\Actions\Shop;

use App\Http\Resources\ItemResource;
use App\Http\Resources\ShopResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Place;
use App\Models\Shop;

class ListShopAction
{
    public function execute($city, $shop,$from=null)
    {

        $placeId = Place::active()->when($city, function ($q) use ($city) {
            $q->where('name', $city);
        })->pluck('id');
        $shops = Shop::where('from','dibimall')->with('Items')->active()->when($placeId, function ($q) use ($placeId) {
            $q->whereIn('place_id', $placeId);
        })->when($shop, function ($q) use ($shop) {
            $q->where('slug', $shop);
        })->get();
        // Shop::with('Items')->get()->dd();

        // $items = Item::active()->when($categoryId, function ($q) use ($categoryId) {
        //     $q->whereIn('category_id', $categoryId);
        // })->get();
        // dd($shops->Items());

        $data = ShopResource::collection($shops);

        // $items = ItemResource::collection($items);
        // $data['items']=$items;
        return [
            'success' => true,
            'data' => $data,
            'message' => 'shops list',
        ];
    }
}
