<?php

namespace App\Http\Controllers;

use App\Actions\Shop\CheckUserNameAction;
use App\Actions\Shop\CreateShopAction;
use App\Actions\Shop\DeleteShopAction;
use App\Actions\Shop\EditShopAction;
use App\Actions\Shop\GetShopsWithSlugAndImage;
use App\Actions\Shop\ListShopAction;
use App\Actions\Shop\OwnerShopListAction;
use App\Actions\Shop\UpdateShopAction;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends BaseController
{
    public function index(ListShopAction $action)
    {
        $city = request('city') ? request('city') : null;
        $shop = request('shop') ? request('shop') : null;
        if(request('from') == 'dibimall')
            $from = 1;
        $response = $action->execute($city, $shop,$from);
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
        $response = $action->execute($request);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function ownerShopList(OwnerShopListAction $action)
    {
        $shopSlug = request('shop') ? request('shop') : null;
        $response = $action->execute($shopSlug);
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function checkUserName(CheckUserNameAction $action)
    {
        request()->validate([
            'user_name' => 'required',
        ]);
        $response = $action->execute(request('user_name'));
        if ($response['success']) {
            return $this->sendSuccess([], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }

    public function shopImageAndSlug(GetShopsWithSlugAndImage $action)
    {
        $response = $action->execute();
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
    public function userNames(Request $request){
        $request->validate([
            'from'=>'required'
        ]);
        $shop = Shop::where('from',request('from'))->pluck('slug');
        return response()->json(['message'=>'user name list','data'=>$shop]);

    }
}
