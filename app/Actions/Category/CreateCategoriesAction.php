<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CreateCategoriesAction
{
    public function execute($request)
    {
        try {
            if ($request->hasFile('image')) {
                $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
                Storage::disk('local')->put('category/'.$fileName, file_get_contents($request->file('image')), 'public');
            }
            Category::create([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'image_name' => $fileName,
                'active' => 1,
            ]);
            $return['success'] = true;
            $return['message'] = 'category added successfully';
        } catch (\Throwable $th) {
            $return['success'] = false;
            $return['message'] = $th->getMessage();
        }

        return $return;
    }
}
