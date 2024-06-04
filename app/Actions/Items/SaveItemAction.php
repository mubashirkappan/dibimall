<?php

namespace App\Actions\Items;


use App\Models\Item;

class SaveItemAction
{
    public function execute(array $data)
    {
        try {
            $item = Item::create($data);
            return [
                'data' => $item,
                'message' => 'Item created successfully',
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
