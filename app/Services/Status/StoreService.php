<?php

namespace App\Services\Status;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        return Status::create($data);
    }
}
