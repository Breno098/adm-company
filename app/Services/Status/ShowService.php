<?php

namespace App\Services\Status;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run($id, array $relations = [])
    {
       $status = Status::find($id);

       if (is_null($status)) {
            return [];
        }

        $status->load($relations);
        
        return $status;
    }
}
