<?php

namespace App\Services\Expense;

use App\Models\Expense;
use Illuminate\Support\Arr;

class StoreExpenseService
{
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

        return $expense;
    }
}
