<?php

namespace App\Services\Item;

use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexActiveService
{
    /**
     * @param  array  $filters
     * @param  bool  $pagination
     * @param  int  $itemsPerPage
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], bool $pagination = false, int $itemsPerPage = 20)
    {
        return Item::with($relations)
            ->filterByName(Arr::get($filters, 'type'))
            ->orderby('name')
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
