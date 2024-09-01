<?php

namespace App\Actions\Shop;

use App\Models\Customer;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\Storage;

class CreateShopAction
{
    public function execute($request)
    {
        try {

            if ($request->hasFile('logo')) {
                $fileName = time().'.'.$request->file('logo')->getClientOriginalExtension();
                Storage::disk('public')->put($fileName, file_get_contents($request->file('logo')), 'public');
                $logoPath = $fileName;
            }
            $userId = auth()->user()->id;
            $shopLimit = Customer::find($userId)->shop_count;
            $shopCount = Shop::where('customer_id', $userId)->count();
            if ($shopCount >= $shopLimit) {
                throw new Exception('you have already added one shop if you add more please contact admin', 1);
            }
            $slug = $request->user_name;
            $this->checkSlug($slug);
            $shop = Shop::create([
                'name' => $request->name,
                'slug' => $request->user_name,
                'address' => $request->address,
                'landmark' => $request->landmark,
                'country_code' => $request->country_code,
                'phone' => $request->phone,
                'email' => $request->email,
                'logo_name' => $logoPath,
                'delivery' => $request->delivery,
                'km' => $request->km,
                'take_away' => $request->take_away,
                'type_id' => $request->type_id,
                'currency' => 'INR',
                'place_id' => $request->place_id,
                'active' => 1,
                'image_count' => 1,
                'customer_id' => $userId,
                'free_delivery_above' => $request->free_delivery_above,
            ]);
            Customer::find($userId)->update(['user_type' => 2]);
            if (! $shop) {
                throw new Exception('something went wrong at shop create', 1);
            }
            $data['data'] = $shop;
            $data['message'] = 'successfully added a shop';
            $data['success'] = true;
        } catch (\Throwable $th) {
            $data['message'] = $th->getMessage();
            $data['success'] = false;
        }

        return $data;
    }

    public function checkSlug($slug)
    {
        $shop = Shop::where('slug', $slug)->first();
        if ($shop) {
            throw new Exception('user_name already taken', 1);
        }
    }
}
