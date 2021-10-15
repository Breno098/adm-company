<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class CountService
{
    /**
     * @param  array  $filters
     * @param  bool $authorized
     *
     * @return mixed
     */
    static public function run(array $filters = [], bool $authorized = true)
    {
        return Client::when($authorized, function (Builder $builder) {
                return $builder->authorizedByCompany();
            })
            ->when(Arr::get($filters, 'name'), function (Builder $builder, $name) {
                return $builder->where('name', 'like', "%{$name}%");
            })
            ->when(Arr::get($filters, 'cpf'), function (Builder $builder, $cpf) {
                return $builder->where('cpf', 'like', "%{$cpf}%");
            })
            ->when(Arr::get($filters, 'cnpj'), function (Builder $builder, $cnpj) {
                return $builder->where('cnpj', 'like', "%{$cnpj}%");
            })
            ->when(Arr::get($filters, 'category_id'), function (Builder $builder, $category_id) {
                return $builder->where('category_id', $category_id);
            })
            ->count();
    }
}
