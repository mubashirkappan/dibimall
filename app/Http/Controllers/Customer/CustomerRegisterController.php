<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\CustomerRegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;

class CustomerRegisterController extends Controller
{
    public function register(CustomerRegisterRequest $request, CustomerRegisterAction $action)
    {
        $validatedData = $request->validated();
        $validatedData['reffered_by'] = request()->reffered_by;
        $customer = $action->execute($validatedData);

        return response()->json($customer, 201);
    }
}
