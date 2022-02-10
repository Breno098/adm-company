<?php

namespace App\Services\Role;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class IndexGroupByTagRoleService
{
    /**
     * @param  array  $filters
     * @param  array  $relations
     * @param  bool|int  $itemsPerPage
     *
     * @return mixed
     */
    public function run()
    {
        return Role::select('tag')
                    ->groupBy('tag')
                    ->get()
                    ->map(function($role) {
                        return [
                            'tag' => $role->tag,
                            'roles' => Role::where('tag', $role->tag)->get()
                        ];
                    });
    }
}
