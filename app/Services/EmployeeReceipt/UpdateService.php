<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  EmployeeReceipt $employeeReceipt
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(EmployeeReceipt $employeeReceipt, array $data = [])
    {
        $employeeReceipt->update($data);

        $employeeReceipt->employee()->associate(Arr::get($data, 'employee_id'));

        $employeeReceipt->save();

        return $employeeReceipt;
    }
}
