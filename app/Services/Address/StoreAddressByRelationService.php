<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class StoreAddressByRelationService
{
    /**
     * @param  array  $data
     * @param Model $instance
     *
     * @return Address
     */
    public function run(array $data = [], $instance)
    {
        if($this->acceptInstance($instance)) {
            return $instance->address()->create([
                'number' => $data['number'],
                'district' => $data['district'],
                'city' => $data['city'],
                'state' => $data['state'],
                'street' => $data['street'],
                'cep' => $data['cep'],
                'complement' => $data['complement'],
                'apartment' => $data['apartment'],
                'floor' => $data['floor'],
                'description' => $data['description'],
                'main' => $data['main'],
                'block' => $data['block'],
                'house' => $data['house'],
                'tower' => $data['tower'],
                'company_id' => $data['company_id'],
                'employee_id' => $data['employee_id'],
                'client_id' => $data['client_id'],
            ]);
        }
    }

      /**
     * @param Model $instance
     *
     * @return bool
     */
    private function acceptInstance($instance): bool
    {
        return $instance instanceof Order || $instance instanceof Client || $instance instanceof Employee || $instance instanceof Company;
    }
}
