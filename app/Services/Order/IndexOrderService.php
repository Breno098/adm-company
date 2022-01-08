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
     * @param  bool $authorized
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false, bool $authorized = true)
    {
        return Order::filterByNameClient(Arr::get($filters, 'client_name'))
            ->filterByAddress(Arr::get($filters, 'address'))
            ->filterByStatus(Arr::get($filters, 'status'))
            ->with($relations)
            ->when($authorized, function (Builder $builder) {
                return $builder->authorizedTenant();
            })
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
