<?php

namespace App\Actions\Category;

use App\Models\Category;

class CreateCategoriesAction
{
    public function execute($request)
    {
        try {
            Category::create([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
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
