<?php

namespace App\Actions\Shop;

use App\Models\Shop;

class DeleteShopAction
{
    public function execute($encrypted_id)
    {
        $shop = Shop::find(decrpyt($encrypted_id));
        if ($shop) {
            $shop->delete();
        } else {
            throw new Exception("can't find a shop to delete", 1);
        }
        $data['message'] = 'successfully delete shop';
        $data['success'] = true;

        return $data;
    }
}
