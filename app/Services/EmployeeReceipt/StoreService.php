<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
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
        $employeeReceipt = EmployeeReceipt::create($data);

        $employeeReceipt->employee()->associate(Arr::get($data, 'employee_id'));

        $employeeReceipt->save();

        return $employeeReceipt;
    }
}
