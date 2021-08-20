<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexActiveService
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
        return Item::with($relations)
            ->filterByType(Arr::get($filters, 'type'))
            ->orderby('name')
            ->when(
                $itemsPerPage,
                function (Builder $builder) use ($itemsPerPage) {
                    return $builder->paginate($itemsPerPage)->withQueryString();
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
