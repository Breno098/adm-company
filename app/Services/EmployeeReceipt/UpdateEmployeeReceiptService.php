<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
use Illuminate\Support\Arr;

class UpdateEmployeeReceiptService
{
    /**
     * @param  EmployeeReceipt $employeeReceipt
     * @param  array  $data
     *
     * @return EmployeeReceipt
     */
    public function run(EmployeeReceipt $employeeReceipt, array $data = [])
    {
        $employeeReceipt->update($data);

        $employeeReceipt->employee()->associate(Arr::get($data, 'employee_id'));

        $employeeReceipt->save();

        return $employeeReceipt;
    }
}
