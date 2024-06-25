<?php

namespace App\Actions\Shop;

use App\Models\OfferImage;
use Illuminate\Support\Facades\Storage;

class OfferImageStoreAction
{
    public function execute($request)
    {
        try {
            if ($request->hasFile('image')) {
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put($fileName, file_get_contents($request->file('image')), 'public');
                $logoPath = $fileName;
            }
            OfferImage::create(['shop_id' => $request->shop_id, 'image' => $logoPath]);
            $return['message'] = 'offer image added successfully';
            $return['success'] = true;
        } catch (\Throwable $th) {
            $return['message'] = $th->getMessage();
            $return['success'] = false;
        }

        return $return;
    }
}
