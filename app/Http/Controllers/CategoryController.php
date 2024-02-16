<?php

namespace App\Http\Controllers;

use App\Actions\Category\ListCategoriesAction;

class CategoryController extends Controller
{
    public function index(ListCategoriesAction $action)
    {
        return $action->execute();
    }
}
