<?php

namespace App\Http\Controllers;
use App\Models\Place;


use Illuminate\Http\Request;

class PlaceController extends BaseController
{
    public function list(Request $request){
        $places = Place::when(request('keyword'),function($q){
            $q->where('name',request('keyword'));
        })->get();
        return $this->sendSuccess($places,'places list');
    }
}
