<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexOrderService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|string $orderBy
     * @param  bool|int  $itemsPerPage
     *
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false)
    {
        return Order::filterByNameClient(Arr::get($filters, 'client_name'))
            ->filterByAddress(Arr::get($filters, 'address'))
            ->filterByStatus(Arr::get($filters, 'status'))
            ->filterByTechnicalVisitDateFrom(Arr::get($filters, 'technical_visit_date_from'))
            ->filterByTechnicalVisitDateTo(Arr::get($filters, 'technical_visit_date_to'))
            ->with($relations)
            ->when($orderBy, function (Builder $builder, $orderBy) {
                $orderBy = explode(':', $orderBy);
                return $builder->orderby($orderBy[0], $orderBy[1] ?? 'asc');
            })
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
