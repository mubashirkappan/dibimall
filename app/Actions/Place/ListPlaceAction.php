<?php

namespace App\Actions\Place;

use App\Http\Resources\PlaceResource;
use App\Models\Place;

class ListPlaceAction
{
    public function execute()
    {
        $places = Place::active()->when(request('keyword'), function ($q) {
            $q->where('name', 'like', '%'.request('keyword').'%');
        })->orderBy('name')->get();

        $data = PlaceResource::collection($places);

        return [
            'success' => true,
            'data' => $data,
            'message' => 'places list',
        ];
    }
}
