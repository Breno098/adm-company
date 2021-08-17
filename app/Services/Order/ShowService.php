<?php

namespace App\Services\Order;

use App\Models\Order;
class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run(Order $order, array $relations = [])
    {
        $order->load($relations)->append([
            'technical_visit_date',
            'technical_visit_hour'
        ]);

        return $order;
    }
}
