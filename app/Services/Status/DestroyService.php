<?php

namespace App\Services\Status;

use App\Models\Status;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run(Status $status)
    {
        $status->active = false;
        $status->save();
        
        return $status;
    }
}
