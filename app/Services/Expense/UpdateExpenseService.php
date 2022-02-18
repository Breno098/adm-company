<?php

namespace App\Services\Expense;

use App\Models\Expense;
use App\Services\Installment\StoreInstallmentByExpenseService;
use Illuminate\Support\Arr;

class UpdateExpenseService
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
     * @param  Expense $expense
     * @param  array  $data
     *
     * @return Expense
     */
    public function run(Expense $expense, array $data = []): Expense
    {
        $expense->update($data);

        $expense->categories()->sync(Arr::get($data, 'categories'));

        $expense->save();

        $expense->installments()->delete();

        foreach ($data['installments'] ?? [] as $installment) {
            $this->storeInstallmentByExpenseService->run($installment, $expense);
        }

        return $expense;
    }
}
