<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;

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
}
