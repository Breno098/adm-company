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
        $data['execution_date_start'] = Arr::get($data, 'date_start') . ' ' . Arr::get($data, 'hour_start');
        $data['execution_date_end'] = Arr::get($data, 'date_end') . ' ' . Arr::get($data, 'hour_end');

        $appointment->update($data);

        $appointment->status()->associate(Arr::get($data, 'status_id'));

        $appointment->save();
        
        return $appointment;
    }
}
