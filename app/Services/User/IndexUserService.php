<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexUserService
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
        return User::with($relations)
            ->when(Arr::get($filters, 'name'), function (Builder $builder, $name) {
                return $builder->where('name', 'like', "%{$name}%");
            })
            ->when($orderBy, function (Builder $builder, $orderBy) {
                return $builder->orderby($orderBy);
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
