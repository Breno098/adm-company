<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use Illuminate\Support\Arr;

class StoreAppointmentService
{
    /**
     * @param array $data
     *
     * @return Appointment
     */
    public function run(array $data = []): Appointment
    {
        Arr::set($data, 'date_end', Arr::get($data, 'date_start'));

        $appointment = Appointment::create($data);

        $appointment->order()->associate(Arr::get($data, 'order_id'));

        $appointment->client()->associate(Arr::get($data, 'client_id'));

        $appointment->address()->associate(Arr::get($data, 'address_id'));

        $appointment->employee()->associate(Arr::get($data, 'employee_id'));

        $appointment->save();

        return $appointment;
    }
}
