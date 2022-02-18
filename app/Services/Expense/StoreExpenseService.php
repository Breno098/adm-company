<?php

namespace App\Services\Expense;

use App\Models\Expense;
use App\Services\Installment\StoreInstallmentByExpenseService;
use Illuminate\Support\Arr;

class StoreExpenseService
{
    protected StoreInstallmentByExpenseService $storeInstallmentByExpenseService;

    /**
     * @param StoreInstallmentByExpenseService $storeInstallmentByExpenseService
     */
    public function __construct(StoreInstallmentByExpenseService $storeInstallmentByExpenseService)
    {
        $this->storeInstallmentByExpenseService = $storeInstallmentByExpenseService;
    }

    /**
     * @param  array  $data
     *
     * @return Expense
     */
    public function run(array $data = []): Expense
    {
        $expense = Expense::create($data);

        $expense->categories()->sync(Arr::get($data, 'categories'));

        $expense->save();

        foreach ($data['installments'] ?? [] as $installment) {
            $this->storeInstallmentByExpenseService->run($installment, $expense);
        }

        return $expense;
    }
}
