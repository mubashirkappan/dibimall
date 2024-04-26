<?php

namespace App\Actions\Item;

use App\Http\Resources\ItemResource;
use App\Models\Item;

class ListItemAction
{
    public function execute()
    {
        $items = Item::all();

        return ItemResource::collection($items);
    }
}
