<?php

namespace App\Actions\Shop;

use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

class UpdateShopAction
{
    public function execute($request)
    {
        $shop = Shop::find(decrypt($request->encrypted_id));
        unset($request->encrypted_id);
        if (! $shop) {
            throw new \Exception("can't find a shop to update", 1);
        } else {
            $logoPath=$shop->logo_name;
            if ($request->hasFile('logo')) {
                if (Storage::disk('public')->exists($shop->logo_name)) {
                    Storage::disk('public')->delete($shop->logo_name);
                }
                $fileName = time().'.'.$request->file('logo')->getClientOriginalExtension();
                Storage::disk('public')->put($fileName, file_get_contents($request->file('logo')), 'public');
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
                'km' => $request->km??$shop->km,
                'take_away' => $request->take_away,
                'type_id' => $request->type_id,
                'place_id' => $request->place_id,
                'free_delivery_above' => $request->free_delivery_above,
            ]);
        }
        $data['message'] = 'successfully updated shop';
        $data['data'] = $shop;
        $data['success'] = true;

        return $data;
    }
}
