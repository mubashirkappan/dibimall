<?php

namespace App\Actions\Item;

use App\Models\Item;
use App\Http\Resources\ItemResource;

class ListItemAction
{
    public function execute()
    {
        $items = Item::all();
        return ItemResource::collection($items);
    }
}
