<?php

namespace App\Services\Position;

use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;

class IndexPositionService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|string $orderBy
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $orderBy = false, $itemsPerPage = false)
    {
        return Position::with($relations)
            ->when($orderBy, function (Builder $builder, $orderBy) {
                return $builder->orderby('name');
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
