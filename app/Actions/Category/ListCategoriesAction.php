<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

class ListCategoriesAction
{
    public function execute()
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }
}