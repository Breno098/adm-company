<?php

namespace App\Services\Service;

use App\Models\Service;
use Illuminate\Support\Arr;

class UpdateServiceService
{
    /**
     * @param Service $service
     * @param array  $data
     *
     * @return Service
     */
    public function run(Service $service, array $data = []): Service
    {
        try {
            $service->update($data);

            $service->categories()->sync(normatizeRelationshipSync(Arr::get($data, 'categories')));

            $service->status()->associate(Arr::get($data, 'status_id'));

            $service->save();

            return $service;
        } catch (\Exception $e) {

        }
    }
}
