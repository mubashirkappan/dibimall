<?php

namespace App\Actions\Items;

use App\Models\Item;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\Storage;

class SaveItemAction
{
    public function execute($request)
    {
        try {
            $shop = Shop::find($request->shop_id);
            if($shop->items->count()>=$shop->item_count){
                throw new Exception("your limit is exceeded to add items please contact admin", 1);
            }
            if ($request->hasFile('image')) {
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put($fileName, file_get_contents($request->file('image')), 'public');
                $logoPath = $fileName;
            }
            $item = Item::create([

                'name' => $request->name,
                'price' => $request->price,
                'dibi_price' => $request->dibi_price,
                'count' => $request->count,
                'image_name' => $logoPath,
                'category_id' => $request->category_id,
                'active' => $request->active ? $request->active : 1,
                'shop_id' => $request->shop_id,
                'offer' => $request->offer ? $request->offer : 0,
                'percentage' => $request->percentage,
                'amount' => $request->amount,
            ]
            );

            return [
                'data' => $item,
                'message' => 'Item created successfully',
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return [
                'message' => $th->getMessage(),
                'success' => false,
            ];
        }
    }
}
