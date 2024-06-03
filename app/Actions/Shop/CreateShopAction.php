<?php

namespace App\Actions\Shop;

use App\Models\Customer;
use App\Models\Place;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\Storage;

class CreateShopAction
{
    public function execute($request)
    {
        
        if ($request->hasFile('logo')) {
            $fileName = time().'.'.$request->file('logo')->getClientOriginalExtension();
            Storage::disk('local')->put('shop_logo/'.$fileName, file_get_contents($request->file('logo')), 'public');
            $logoPath = $fileName;
        }
        $userId = auth()->user()->id;
        $place = Place::find($request->place_id)->name;
        $slug = $request->name.$place;
        $slugValue = $this->checkSlug($slug);
        $shop = Shop::create([
            'name'=>$request->name,
            'slug'=>$request->user_name,
            'address'=>$request->address,
            'landmark'=>$request->landmark,
            'country_code'=>$request->country_code,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'logo_name'=>$logoPath ,
            'delivery'=>$request->delivery,
            'km'=>$request->km,
            'take_away'=>$request->take_away,
            'type_id'=>$request->type_id,
            'place_id'=>$request->place_id,
            'active'=>1,
            'image_count'=>1,
            'customer_id'=>$userId
        ]);
        Customer::find($userId)->update(['user_type' => 2]);
        if (! $shop) {
            throw new Exception('something went wrong at shop create', 1);
        }
        $data['message'] = 'successfully added a shop';
        $data['data'] = $shop;
        $data['success'] = true;

        return $data;
    }
    public function checkSlug($slug){
        $shop = Shop::where('slug',$slug)->first();
        if($shop){
            $random_number = rand(1,100);
            $slug = $slug.$random_number;
            $this->checkSlug($slug);
        }
        return $slug;
    }
}
