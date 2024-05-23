<?php

namespace App\Http\Controllers;

use App\Actions\Shop\CreateShopAction;
use App\Actions\Shop\DeleteShopAction;
use App\Actions\Shop\EditShopAction;
use App\Actions\Shop\ListShopAction;
use App\Actions\Shop\OwnerShopListAction;
use App\Http\Requests\CreateShopRequest;

class ShopController extends BaseController
{
    public function index(ListShopAction $action)
    {
        $city = request('city') ? request('city') : null;
        $shop = request('shop') ? request('shop') : null;

        $response = $action->execute($city, $shop);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function delete(DeleteShopAction $action, $encrypted_id)
    {
        $response = $action->execute($encrypted_id);
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function edit(EditShopAction $action, $encrypted_id)
    {
        $response = $action->execute($encrypted_id);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function update(UpdateShopRequest $request, UpdateShopAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function create(CreateShopRequest $request, CreateShopAction $action)
    {
        $response = $action->execute($request->validated());
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    public function ownerShopList(OwnerShopListAction $action){
        $response = $action->execute();
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
