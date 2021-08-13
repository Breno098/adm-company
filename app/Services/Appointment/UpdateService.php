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
    static public function run($id, array $data = [])
    {
        $appointment = Appointment::find($id);

        if(!$appointment){
            return $appointment;
        }

        $appointment->update($data);

        $appointment->status()->associate(Arr::get($data, 'status'));

        $appointment->save();
        
        return $appointment;
    }
}
