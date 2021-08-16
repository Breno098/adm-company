<?php

namespace App\Services\Order;

use App\Models\Order;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run($id)
    {
       $order = Order::find($id);

        if (is_null($order)) {
            return [];
        }

        $order->active = false;
        $order->save();
        
        return $order;
    }
}
