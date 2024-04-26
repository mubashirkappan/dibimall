<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerLoginAction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CustomerLoginRequest;

class CustomerLoginController extends BaseController
{
    public function login(CustomerLoginRequest $request, CustomerLoginAction $action)
    {
        $response = $action($request->validated());
        if ($response['success']) {
            return $this->sendSuccess($response['data'], $response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
