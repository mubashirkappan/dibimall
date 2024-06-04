<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class UpdateCategoriesAction
{
    public function execute($request)
    {

        $category = Category::find(decrypt($request->encrypted_id));
        $fileName = $category->image_name;
        if ($request->hasFile('image')) {
            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put('category/'.$fileName, file_get_contents($request->file('image')), 'public');
        }
        $category->update([
            'shop_id' => $request->shop_id,
            'image_name' => $fileName,
            'name' => $request->name,
        ]);
        $return['success'] = true;
        $return['message'] = 'category updated successfuly';

        return $return;
    }
}
