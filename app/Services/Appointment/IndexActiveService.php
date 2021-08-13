<?php

namespace App\Services\Appointment;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexActiveService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool  $pagination
     * @param  int  $itemsPerPage
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], bool $pagination = false, int $itemsPerPage = 20)
    {
        return Appointment::with($relations)
            ->filterByDate(Arr::get($filters, 'date'))
            ->filterByStatusId(Arr::get($filters, 'status_id'))
            ->orderby('date', 'desc')
            // ->append(['date_format',  'hour_format'])
            ->when(
                $pagination,
                function (Builder $builder) use ($itemsPerPage) {
                    return $builder->paginate($itemsPerPage)->withQueryString();
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
