<?php

namespace App\Http\Controllers;

class ReferController extends BaseController
{
    public function refer()
    {

        $url = auth()->user()->referal_code;

        return $this->sendSuccess($url, 'referal code');
    }
}
