<?php

namespace App\Services\Expense;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        $expense = Expense::create($data);

        $expense->categories()->sync(Arr::get($data, 'categories'));

        $expense->save();

        return $expense;
    }
}
