<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;

class CustomerRegisterController extends Controller
{
    public function register(CustomerRegisterRequest $request, CustomerRegisterAction $action)
    {
        $customer = $action->execute($request->validated());

        return response()->json($customer, 201);
    }
}

