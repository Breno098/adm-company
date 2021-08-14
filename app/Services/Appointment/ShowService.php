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
                'date_start',
                'hour_start',
                'date_end',
                'hour_end'
            ])->load($relations);
        }
        
        return $appointment;
    }
}
