<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], $itemsPerPage = false)
    {
        return Category::with($relations)
            ->filterByName(Arr::get($filters, 'name'))
            ->filterByType(Arr::get($filters, 'type'))
            ->orderby('name')
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
