<?php

namespace App\Actions\Items;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class UpdateItemAction
{
    public function execute($request, $id)
    {
        try {
            $item = Item::findOrFail($id);
            $logoPath=$item->image_name;
            if ($request->hasFile('image')) {
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->put($fileName, file_get_contents($request->file('image')), 'public');
                $logoPath = $fileName;
            }
            $item->update([
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
            ]);

            return [
                'data' => $item,
                'message' => 'Item updated successfully',
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
