<?php

namespace App\Actions\Shop;

use App\Models\Order;
use Exception;

class AcceptOrderAction
{
    public function execute($order_id)
    {
        $data = Order::find($order_id);
        if ($data) {
            $data->update(['is_accepted_by_owner' => 1]);
        } else {
            throw new Exception('no order found', 1);
        }
        $return['success'] = true;
        $return['message'] = 'order accepted succesffuly';
        $return['data'] = $data;

        return $return;
    }
}
