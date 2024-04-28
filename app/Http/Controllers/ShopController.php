<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Actions\Shop\ListShopAction;
use App\Actions\Shop\DeleteShopAction;
use App\Actions\Shop\EditShopAction;
use App\Actions\Shop\UdateShopAction;
use App\Actions\Shop\CreateShopAction;
use App\Http\Requests\CreateShopRequest;
use App\Http\Resources\ShopResource;

class ShopController extends BaseController
{
    public function index(ListShopAction $action)

    {    $city = request('city') ? request('city') : null;
        $shop = request('shop') ? request('shop') : null;
        $category = request('category') ? request('category') : null;

        $response= $action->execute($city, $shop, $category);    
        if ($response['success']) {
            return $this->sendSuccess($response['data'],$response['message']);
        }else{
            return $this->sendError($response['message']);
        }
    }
    public function delete(DeleteShopAction $action,$encrypted_id)
    {
        $response = $action->execute($encrypted_id);
        if ($response['success']) {
            return $this->sendSuccess([],$response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    public function edit(EditShopAction $action,$encrypted_id)
    {
        $response = $action->execute($encrypted_id);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    public function update(UpdateShopRequest $request,UpdateShopAction $action)
    {
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    public function create(CreateShopRequest $request,CreateShopAction $action)
    {
        $response = $action->execute($request->validated());
         if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    }
