<?php

namespace App\Services\Employee;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class IndexEmployeeService
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
        return Employee::with($relations)
            ->when(Arr::get($filters, 'name'), function (Builder $builder, $name) {
                return $builder->where('name', 'like', "%{$name}%");
            })
            ->when(Arr::get($filters, 'cpf'), function (Builder $builder, $cpf) {
                return $builder->where('cpf', 'like', "%{$cpf}%");
            })
            ->when(Arr::get($filters, 'rg'), function (Builder $builder, $rg) {
                return $builder->where('rg', 'like', "%{$rg}%");
            })
            ->when(Arr::get($filters, 'position_id'), function (Builder $builder, $position_id) {
                return $builder->where('position_id', $position_id);
            })
            ->when($orderBy, function (Builder $builder, $orderBy) {
                return $builder->orderby($orderBy);
            })
            ->when(
                $itemsPerPage,
                function (Builder $builder, $itemsPerPage) {
                    return $builder->paginate($itemsPerPage);
                },
                function (Builder $builder) {
                    return $builder->get();
                }
            );
    }
}
