<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(){
        $user = auth()->user();
        return UserResource::collection($user);
    } 
}
