<?php

namespace App\Actions\Shop;

use App\Models\Customer;
use App\Models\Shop;
use Exception;

class CreateShopAction
{
    public function execute(array $request)
    {

        $request['active'] = 1;
        $request['image_count'] = 1;
        $request['active'] = 1;
        $userId = auth()->user()->id;
        $request['customer_id'] = $userId;
        $shop = Shop::create($request);
        Customer::find($userId)->update(['user_type' => 2]);
        if (! $shop) {
            throw new Exception('something went wrong at shop create', 1);
        }
        $data['message'] = 'successfully added a shop';
        $data['data'] = $shop;
        $data['success'] = true;

        return $data;
    }
}
