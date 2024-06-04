<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\Item;
use Exception;

class DeleteCategoriesAction
{
    public function execute($encrypted_id)
    {
        try {

            $itemWithCategory = Item::where('category_id', decrypt($encrypted_id))->exists();
            if ($itemWithCategory) {
                throw new Exception('category is connecteed with a item', 1);
            }
            $category = Category::find(decrypt($encrypted_id))->delete();
            $return['success'] = true;
            $return['message'] = 'category deleted successfully';
        } catch (\Throwable $th) {
            $return['success'] = false;
            $return['message'] = $th->getMessage();
        }

        return $return;
    }
}
