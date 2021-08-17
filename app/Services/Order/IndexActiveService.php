<?php

namespace App\Services\Order;

use App\Models\Order;
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
        return Order::with($relations)
            ->filterByStatusId(Arr::get($filters, 'status_id'))
            ->orderBy('created_at', 'desc')
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
