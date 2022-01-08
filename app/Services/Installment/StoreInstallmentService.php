<?php

namespace App\Services\Installment;

use App\Models\Installment;

class StoreInstallmentService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(array $data = [])
    {
        return Installment::create([
            "number" => $data['number'],
            "form_of_payment" => $data['form_of_payment'],
            "status" => $data['status'],
            "due_date" => $data['due_date'],
            "pay_day" => $data['pay_day'],
            "value" => $data['value'],
        ]);
    }
}
