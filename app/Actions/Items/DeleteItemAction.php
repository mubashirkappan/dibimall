<?php

namespace App\Actions\Items;

use App\Models\Item;

class DeleteItemAction
{
    public function execute($id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();

            return [
                'message' => 'Item deleted successfully',
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
