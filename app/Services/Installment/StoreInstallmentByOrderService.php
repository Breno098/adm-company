<?php

namespace App\Services\Installment;

use App\Models\Installment;
use App\Models\Order;

class StoreInstallmentByOrderService
{
    /**
     * @param array $data
     * @param Order $order
     *
     * @return Installment
     */
    public function run(array $data = [], Order $order): Installment
    {
        return $order->installments()->create([
            "number" => $data['number'],
            "payment_method" => $data['payment_method'],
            "status" => $data['status'],
            "due_date" => $data['due_date'],
            "pay_day" => $data['pay_day'],
            "value" => $data['value'],
        ]);
    }
}
