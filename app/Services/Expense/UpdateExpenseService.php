<?php

namespace App\Services\Expense;

use App\Models\Expense;
use Illuminate\Support\Arr;

class UpdateExpenseService
{
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

        return $expense;
    }
}
