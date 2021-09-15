<?php

namespace App\Services\Service;

use App\Models\Service;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Service $service, array $data = [])
    {
        $service->update($data);

        $service->categories()->sync(Arr::get($data, 'categories'));

        $service->status()->associate(Arr::get($data, 'status_id'));

        $service->save();

        return $service;
    }
}
