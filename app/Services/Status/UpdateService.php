<?php

namespace App\Services\Status;

use App\Models\Status;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run($id, array $data = [])
    {
        $status = Status::find($id);

        if(!$status){
            return $status;
        }

        $status->update($data);

        return $status;
    }
}
