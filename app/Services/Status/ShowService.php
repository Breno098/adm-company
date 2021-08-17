<?php

namespace App\Services\Status;

use App\Models\Status;
class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run(Status $status, array $relations = [])
    {
        $status->load($relations);
        
        return $status;
    }
}
