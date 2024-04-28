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
}
