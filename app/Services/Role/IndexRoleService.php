<?php

namespace App\Services\Role;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class IndexRoleService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    public function run(array $filters = [], array $relations = [], $itemsPerPage = false)
    {
        return Role::with($relations)
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
