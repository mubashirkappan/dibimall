<?php

namespace App\Http\Controllers;

use App\Actions\Item\ListItemAction;

class ItemController extends Controller
{
    public function index(ListItemAction $action)
    {
        return $action->execute();
    }
}
