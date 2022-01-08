<?php

namespace App\Services\Employee;

use App\Models\Employee;
use Illuminate\Support\Arr;

class UpdateEmployeeService
{
    /**
     * @param Employee $employee
     * @param array  $data
     *
     * @return Employee
     */
    public function run(Employee $employee, array $data = []): Employee
    {
        $employee->update($data);

        $employee->contacts()->delete();
        $employee->addresses()->delete();

        foreach ($data['contacts'] as $contact) {
            $id = isset($contact['id']) ? $contact['id'] : false;

            if($id){
                $contactEmployee = $employee->contacts()->withTrashed()->find($id);
                $contactEmployee->restore();
                $contactEmployee->update($contact);
            } else {
                $verifyContact = isset($contact['contact']);
                if($verifyContact){
                    $employee->contacts()->create($contact);
                }
            }
        }

        foreach ($data['addresses'] as $address) {
            $id = isset($address['id']) ? $address['id'] : false;

            if($id){
                $addressEmployee = $employee->addresses()->withTrashed()->find($id);
                $addressEmployee->restore();
                $addressEmployee->update($address);
            } else {
                $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
                if($verifyAddress){
                    $employee->addresses()->create($address);
                }
            }
        }

        $employee->position()->associate(Arr::get($data, 'position_id'));

        $employee->save();

        return $employee;
    }
}
