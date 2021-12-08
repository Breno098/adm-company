<?php

namespace App\Services\Employee;

use App\Models\Employee;
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
        $employee = Employee::create($data);

        foreach ($data['contacts'] ?? [] as $contact) {
            $verifyContact = isset($contact['contact']);
            if($verifyContact){
                $employee->contacts()->create($contact);
            }
        }

        foreach ($data['addresses'] ?? [] as $address) {
            $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
            if($verifyAddress){
                $employee->addresses()->create($address);
            }
        }

        $employee->position()->associate(Arr::get($data, 'position_id'));

        $employee->save();

        return $employee;
    }
}
