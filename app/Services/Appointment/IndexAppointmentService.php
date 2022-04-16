<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexAppointmentService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $itemsPerPage = false)
    {
        return Appointment::with($relations)
            ->filterByBetweenDate(Arr::get($filters, 'date_start'),Arr::get($filters, 'date_end'))
            ->filterByBetweenTime(Arr::get($filters, 'time_start'),Arr::get($filters, 'time_end'))
            ->filterByConcluded(Arr::get($filters, 'concluded'))
            ->filterByClientName(Arr::get($filters, 'client_name'))
            ->filterByAddress(Arr::get($filters, 'address'))
            ->filterByEmployee(Arr::get($filters, 'employee_id'))
            ->filterById(Arr::get($filters, 'id'))
            ->filterByNotId(Arr::get($filters, 'not_id'))
            ->orderby('date_start', 'desc')
            ->when(
                $itemsPerPage,
                function (Builder $builder) use ($itemsPerPage) {
                    return $builder->paginate($itemsPerPage);
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
