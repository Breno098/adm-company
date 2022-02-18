<?php

namespace App\Services\Installment;

use App\Models\Expense;
use App\Models\Installment;

class StoreInstallmentByExpenseService
{
    /**
     * @param array $data
     * @param Expense $expense
     *
     * @return Installment
     */
    public function run(array $data = [], Expense $expense): Installment
    {
        return $expense->installments()->create([
            "number" => $data['number'],
            "payment_method" => $data['payment_method'],
            "status" => $data['status'],
            "due_date" => $data['due_date'],
            "pay_day" => $data['pay_day'],
            "value" => $data['value'],
        ]);
    }
}
