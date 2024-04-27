<?php

namespace App\Http\Controllers;
use App\Actions\Place\ListPlaceAction;
use App\Models\Place;


use Illuminate\Http\Request;

class PlaceController extends BaseController
{
    public function list(Request $request,ListPlaceAction $action){
        $response = $action->execute();
        if ($response['success']) {
            return $this->sendSuccess($response['data'],$response['message']);
        } else {
            return $this->sendError($response['message']);
        }
    }
}
