<?php

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexAddressService
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
        return Address::with($relations)
            ->filterClient(Arr::get($filters, 'client_id'))
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
