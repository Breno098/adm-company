<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|string $orderBy
     * @param  bool|int  $itemsPerPage
     * @param  bool $authorized
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false, bool $authorized = true)
    {
        return Order::with($relations)
            ->when($authorized, function (Builder $builder) {
                return $builder->authorizedTenant();
            })
            ->filterByStatusId(Arr::get($filters, 'status_id'))
            ->filterByStatusType(Arr::get($filters, 'status_type'))
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
