<?php

namespace App\Services\Installment;

use App\Models\Installment;
use App\Models\Order;
use Illuminate\Support\Arr;

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
            "number" => Arr::get($data, 'number', $order->installments()->count() + 1),
            "payment_method" => Arr::get($data, 'payment_method'),
            "status" => Arr::get($data, 'status'),
            "due_date" => Arr::get($data, 'due_date'),
            "pay_day" => Arr::get($data, 'pay_day'),
            "value" => Arr::get($data, 'value'),
        ]);
    }
}
