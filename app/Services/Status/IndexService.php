<?php

namespace App\Services\Status;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|string $orderBy
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false)
    {
        return Status::with($relations)
            ->filterByType(Arr::get($filters, 'type'))
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
