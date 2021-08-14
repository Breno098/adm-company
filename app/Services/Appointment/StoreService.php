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
        $data['execution_date_start'] = Arr::get($data, 'date_start') . ' ' . Arr::get($data, 'hour_start');
        $data['execution_date_end'] = Arr::get($data, 'date_end') . ' ' . Arr::get($data, 'hour_end');

        $appointment = Appointment::create($data);

        $appointment->order()->associate(Arr::get($data, 'order_id'));
        $appointment->status()->associate(Arr::get($data, 'order_id'));
        
        $appointment->save();
        
        return $appointment;
    }
}
