<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexService
{
    /**
     * @return mixed
     */
    static public function run()
    {
        return Client::with(request()->get('relations', []))
            ->when(request()->get('name'), function (Builder $builder, $name) {
                return $builder->where('name', 'like', "%{$name}%");
            })
            ->when(request()->get('cpf'), function (Builder $builder, $cpf) {
                return $builder->where('cpf', 'like', "%{$cpf}%");
            })
            ->when(request()->get('cnpj'), function (Builder $builder, $cnpj) {
                return $builder->where('cnpj', 'like', "%{$cnpj}%");
            })
            ->when(request()->get('category_id'), function (Builder $builder, $category_id) {
                return $builder->where('category_id', $category_id);
            })
            ->orderby(request()->get('orderBy'))
            ->when(
                request()->get('itemsPerPage'),
                function (Builder $builder, $itemsPerPage) {
                    return $builder->paginate($itemsPerPage);
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
