<?php

namespace App\Http\Controllers;

use App\Actions\Shop\OfferImageStoreAction;
use App\Http\Requests\OfferImageRequest;
use App\Models\OfferImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OfferController extends BaseController
{
    public function MainIndex(){
        try {
            $adminAddedOffer = OfferImage::where('is_admin_added',1)->get();
            return $this->sendSuccess($adminAddedOffer,'dibimall offers');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function InsideShopIndex(){
        try {
            request()->validate([
                'shop_id'=>'required'
            ]);
            $adminAddedOffer = OfferImage::where('shop_id',request('shop_id'))->get();
            return $this->sendSuccess($adminAddedOffer,'single shop offers');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function RandomIndex(){
        try {
            $distinctOffers = OfferImage::select('shop_id', DB::raw('MIN(id) as min_id'))
        ->groupBy('shop_id')
        ->get()
        ->map(function($item) {
            return OfferImage::find($item->min_id);
        });    
        $randomizedOffers = $distinctOffers->shuffle()->take(10);
        return $this->sendSuccess($randomizedOffers,'random offer images');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function addImage(OfferImageRequest $request,OfferImageStoreAction $action){
        try {
            $respose = $action->execute($request);
            return $this->sendSuccess([],$respose['message']);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function delete($id){
        $offer = OfferImage::find($id);
        if (Storage::disk('public')->exists($offer->image)) {
            Storage::disk('public')->delete($offer->image);
        }
        $offer->delete();
        return $this->sendSuccess([],'offer deleted');
    }
}
