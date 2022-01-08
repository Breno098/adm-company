<?php

namespace App\Services\Service;

use App\Models\Service;
use Illuminate\Support\Arr;

class StoreServiceService
{
    /**
     * @param array $data
     *
     * @return Service
     */
    public function run(array $data = []): Service
    {
        $service = Service::create($data);

        $service->categories()->sync(Arr::get($data, 'categories'));

        $service->status()->associate(Arr::get($data, 'status_id'));

        $service->save();

        return $service;
    }
}
