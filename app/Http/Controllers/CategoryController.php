<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategoriesAction;
use App\Actions\Category\DeleteCategoriesAction;
use App\Actions\Category\ListCategoriesAction;
use App\Actions\Category\UpdateCategoriesAction;
use App\Http\Requests\CreateCategoriesRequest;
use App\Http\Requests\UpdateCategoriesRequest;

class CategoryController extends BaseController
{
    public function index(ListCategoriesAction $action)
    {
        request()->validate([
            'shop_id' => 'required',
        ]);
        $response = $action->execute(request('shop_id'));
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function create(CreateCategoriesRequest $request, CreateCategoriesAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function delete($encrypted_id, DeleteCategoriesAction $action)
    {
        $response = $action->execute($encrypted_id);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function update(UpdateCategoriesRequest $request, UpdateCategoriesAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
