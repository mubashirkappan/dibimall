<?php

namespace App\Actions\Shop;

use App\Models\Order;
use App\Models\Shop;
use Exception;

class GetShopsWithSlugAndImage
{
    public function execute()
    {
        $shops = Shop::active()->select('slug','logo_name')->get();
        $return['success'] = true;
        $return['message'] = 'order accepted succesffuly';
        $return['data'] = $shops;

        return $return;
    }
}
