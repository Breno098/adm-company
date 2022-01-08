<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
use Illuminate\Support\Arr;

class StoreEmployeeReceiptService
{
    /**
     * @param  array  $data
     *
     * @return EmployeeReceipt
     */
    public function run(array $data = []): EmployeeReceipt
    {
        $employeeReceipt = EmployeeReceipt::create($data);

        $employeeReceipt->employee()->associate(Arr::get($data, 'employee_id'));

        $employeeReceipt->save();

        return $employeeReceipt;
    }
}
