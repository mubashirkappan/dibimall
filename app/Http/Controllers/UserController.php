<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\TrackPhonenumberClickedUser;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function getUser()
    {
        $user = auth()->user();
        $data = new UserResource($user);

        return $this->sendSuccess($data, 'user details');
    }

    public function updateToOwner()
    {
        $user = auth()->user();
        if ($user->user_type == 1) {
            $user->user_type = 3;
            $user->save();
        } else {
            return $this->sendError('you are already owner');
        }

        return $this->sendSuccess([], 'user requested for ownership');
    }

    public function trackPhoneClick()
    {
        request()->validate([
            'shop_id' => 'required',
        ]);
        TrackPhonenumberClickedUser::create(['shop_id' => request('shop_id'), 'customer_id' => auth()->user()->id]);

        return $this->sendSuccess([], 'user can show the number');
    }
    public function resetPassword()
    {
        request()->validate([
            'new_password' => 'required',
            'old_password' => 'required',
        ]);
        $customer = Customer::find(auth()->user()->id);
        if ($customer && (Hash::check(request('old_password'), $customer->password) || md5(request('old_password')) == $customer->password)) {
            // Rehash the password with Bcrypt
            $customer->password = request('new_password');
            $customer->save();
            return $this->sendSuccess([], 'password updated successfully');
            // return response()->json(['success' => 'Password updated.']);
        } else {
            return $this->sendError('Old password is incorrect.');

            // return response()->json(['error' => 'Old password is incorrect.'], 401);
        }

    }
}