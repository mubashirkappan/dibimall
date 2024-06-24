<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\TrackPhonenumberClickedUser;

class UserController extends BaseController
{
    public function getUser()
    {
        $user = auth()->user();
        $data = new UserResource($user);

        return $this->sendSuccess($data, 'user details');
    }
    public function updateToOwner(){
        $user = auth()->user();
        if($user->user_type == 1){
            $user->user_type =3;
            $user->save();
        }else{
            return $this->sendError('you are already owner');
        }
        return $this->sendSuccess([], 'user requested for ownership');
    }
    public function trackPhoneClick(){
        request()->validate([
            'shop_id' => 'required',
        ]);
        TrackPhonenumberClickedUser::create(['shop_id'=>request('shop_id'),'customer_id'=>auth()->user()->id]);
        return $this->sendSuccess([], 'user can show the number');
    }
}
