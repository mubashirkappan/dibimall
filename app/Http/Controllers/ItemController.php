<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Item\ListItemAction;

class ItemController extends Controller
{
    public function index(ListItemAction $action)
    {
        return $action->execute();
    }
}
