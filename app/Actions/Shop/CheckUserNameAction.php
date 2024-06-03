<?php

namespace App\Actions\Shop;

use App\Models\Customer;
use App\Models\Place;
use App\Models\Shop;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Storage;

class CheckUserNameAction
{
    public function execute($userName)
    {
        try {
            $shop = Shop::where('slug',$userName)->first();
            if($shop){
                throw new Exception("UserName already taken", 1);
            }
            $return['success']=false;
            $return['message']='you can use the username';
        } catch (\Throwable $th) {
            $return['success']=false;
            $return['message']=$th->getMessage();
        }
        return $return;
    }
    }
