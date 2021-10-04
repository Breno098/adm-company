<?php

namespace App\Services\Client;

use App\Models\Client;
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
        return Client::with($relations)
            ->filterByName(Arr::get($filters, 'name'))
            ->filterByCpf(Arr::get($filters, 'cpf'))
            ->filterByCnpj(Arr::get($filters, 'cnpj'))
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
