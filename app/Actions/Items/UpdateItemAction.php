<?php

namespace App\Actions\Items;

use App\Models\Item;

class UpdateItemAction
{
    public function execute(array $data, $id)
    {
        try {
            $item = Item::findOrFail($id);
            $item->update($data);

            return [
                'data' => $item,
                'message' => 'Item updated successfully',
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
