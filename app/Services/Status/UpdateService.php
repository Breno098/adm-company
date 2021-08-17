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
    static public function run(Status $status, array $data = [])
    {
        $status->update($data);

        return $status;
    }
}
