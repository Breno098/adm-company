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
            ->filterByConcluded(Arr::get($filters, 'concluded'))
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
