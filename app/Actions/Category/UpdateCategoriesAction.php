<?php

namespace App\Actions\Category;

use App\Models\Category;

class UpdateCategoriesAction
{
    public function execute($request)
    {

        $category = Category::find(decrypt($request->encrypted_id));
        $category->update([
            'shop_id' => $request->shop_id,
            'name' => $request->name,
        ]);
        $return['success'] = true;
        $return['message'] = 'category updated successfully';

        return $return;
    }
}
