<?php

namespace App\Actions\Item;

use App\Http\Resources\ItemResource;
use App\Models\Item;

class ListItemAction
{
    public function execute($request)
    {
        $items = Item::active()->when($request['category_id'], function ($q) use ($request) {
            $q->where('category_id', $request['category_id']);
        })->when($request['keyword'], function ($q) use ($request) {
            $q->where('name', 'like', '%'.$request['keyword'].'%');
        })->where('shop_id', $request['shop_id'])
            ->orderBy('category_id')->get();

        $data = ItemResource::collection($items);

        return [
            'success' => true,
            'data' => $data,
            'message' => 'Item list',
        ];
    }
}
