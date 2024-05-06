<?php

namespace App\Actions\Category;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

class ListCategoriesAction
{
    public function execute()
    {
        $categories = Category::active()->get();

        return CategoryResource::collection($categories);
    }
}
