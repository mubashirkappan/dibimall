<?php

namespace App\Http\Controllers;

class ReferController extends BaseController
{
    public function refer()
    {

        $url = env('APP_URL').'customer-register/'.'?reffered_by='.auth()->user()->referal_code;

        return $this->sendSuccess($url, 'referal link');
    }
}
