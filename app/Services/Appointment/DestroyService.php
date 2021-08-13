<?php

namespace App\Services\Appointment;

use App\Models\Appointment;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run($id)
    {
       $appointment = Appointment::find($id);

        if (is_null($appointment)) {
            return [];
        }

        $appointment->active = false;
        $appointment->save();
        
        return $appointment;
    }
}
