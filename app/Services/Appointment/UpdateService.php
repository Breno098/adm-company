<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Appointment $appointment, array $data = [])
    {
        $appointment->update($data);

        $appointment->order()->associate(Arr::get($data, 'order_id'));

        $appointment->client()->associate(Arr::get($data, 'client_id'));

        $appointment->address()->associate(Arr::get($data, 'address_id'));

        $appointment->save();
        
        return $appointment;
    }
}
