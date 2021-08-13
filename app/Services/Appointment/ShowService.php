<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run($id, array $relations = [])
    {
        $appointment = Appointment::find($id);

        if($appointment){
            $appointment->append([
                'date_start_format',
                'hour_start_format',
                'date_end_format',
                'hour_end_format'
            ])->load($relations);
        }
        
        return $appointment;
    }
}
