<?php

namespace App\Actions\Items;

use App\Models\Item;

class IndexItemsAction
{
    public function execute($shopId)
    {
        try {
            $items = Item::where('shop_id', $shopId)->get();
            return [
                'data' => $items,
                'message' => 'Items retrieved successfully',
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return [
                'message' => $th->getMessage(),
                'success' => false,
            ];
        }
    }
}
