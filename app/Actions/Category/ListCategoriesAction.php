<?php

namespace App\Actions\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

class ListCategoriesAction
{
    public function execute($shopId)
    {
        $categories = Category::where('shop_id', $shopId)->active()->get();
        $return['data'] = CategoryResource::collection($categories);
        $return['message'] = 'category list agianst shop';
        $return['success'] = true;

        return $return;

    }
}
