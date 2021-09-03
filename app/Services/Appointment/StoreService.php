<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
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
        $appointment = Appointment::create($data);

        $appointment->order()->associate(Arr::get($data, 'order_id'));

        $appointment->client()->associate(Arr::get($data, 'client_id'));

        $appointment->address()->associate(Arr::get($data, 'address_id'));
        
        $appointment->save();
        
        return $appointment;
    }
}
