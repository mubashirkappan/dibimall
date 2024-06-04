<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class UpdateShopAction
{
    public function execute($request)
    {
        $shop = Shop::find($request->encrypted_id);
        unset($request->encrypted_id);
        if (! $shop) {
            throw new \Exception("can't find a shop to delete", 1);
        } else {
            if ($request->hasFile('logo')) {
                if (Storage::disk('local')->exists('shop_logo/'.$shop->logo_name)) {
                    Storage::disk('local')->delete('shop_logo/'.$shop->logo_name);
                }
                $fileName = time().'.'.$request->file('logo')->getClientOriginalExtension();
                Storage::disk('local')->put('shop_logo/'.$fileName, file_get_contents($request->file('logo')), 'public');
                $logoPath = $fileName;
            }
            $shop->update([
                'name' => $request->name,
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
                'place_id' => $request->place_id,
            ]);
        }
        $data['message'] = 'successfully updated shop';
        $data['data'] = $shop;
        $data['success'] = true;

        return $data;
    }
}
