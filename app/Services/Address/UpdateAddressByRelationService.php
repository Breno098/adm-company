<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class UpdateAddressByRelationService
{
    /**
     * @param  array  $data
     * @param Model $instance
     *
     * @return Address
     */
    public function run($instance, array $data = [])
    {
        if($this->acceptInstance($instance)) {
            return $instance->address()->update([
                'number' => $data['number'] ?? null,
                'district' => $data['district'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'street' => $data['street'] ?? null,
                'cep' => $data['cep'] ?? null,
                'complement' => $data['complement'] ?? null,
                'apartment' => $data['apartment'] ?? null,
                'floor' => $data['floor'] ?? null,
                'description' => $data['description'] ?? null,
                'main' => $data['main'] ?? null,
                'block' => $data['block'] ?? null,
                'house' => $data['house'] ?? null,
                'tower' => $data['tower'] ?? null,
                'company_id' => $data['company_id'] ?? null,
                'employee_id' => $data['employee_id'] ?? null,
                'client_id' => $data['client_id'] ?? null,
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
