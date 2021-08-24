<?php

namespace App\Services\Expense;

use App\Models\Expense;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  Expense $expense
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Expense $expense, array $data = [])
    {
        $expense->update($data);

        $expense->categories()->sync(Arr::get($data, 'categories'));

        $expense->save();

        return $expense;
    }
}
