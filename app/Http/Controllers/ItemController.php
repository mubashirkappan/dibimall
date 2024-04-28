<?php

namespace App\Http\Controllers;

use App\Actions\Item\ListItemAction;
use App\Http\Requests\ListItemRequest;

class ItemController extends BaseController
{
    public function index(ListItemRequest $request, ListItemAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
