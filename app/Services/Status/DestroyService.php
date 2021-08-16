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
    static public function run($id)
    {
       $status = Status::find($id);

        if (is_null($status)) {
            return [];
        }

        $status->active = false;
        $status->save();
        
        return $status;
    }
}
