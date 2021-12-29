<?php

namespace App\Services\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|int  $itemsPerPage
     * @param  bool $authorized
     *
     * @return mixed
     */
    static public function run(array $filters = [], array $relations = [], $itemsPerPage = false, bool $authorized = true)
    {
        return Company::with($relations)
            ->when($authorized, function (Builder $builder) {
                return $builder->authorizedTenant();
            })
            ->filterByName(Arr::get($filters, 'name'))
            ->filterByCpf(Arr::get($filters, 'cpf'))
            ->filterByCnpj(Arr::get($filters, 'cnpj'))
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
