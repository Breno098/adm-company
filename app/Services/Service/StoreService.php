<?php

namespace App\Services\Service;

use App\Models\Service;
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
        $service = Service::create($data);

        $service->categories()->sync(Arr::get($data, 'categories'));

        $service->status()->associate(Arr::get($data, 'status_id'));

        $service->save();

        return $service;
    }
}
