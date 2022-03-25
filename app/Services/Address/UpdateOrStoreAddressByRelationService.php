<?php

namespace App\Services\Address;

use App\Models\Address;
use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class UpdateOrStoreAddressByRelationService
{
    protected UpdateAddressByRelationService $updateAddressByRelationService;

    protected StoreAddressByRelationService $storeAddressByRelationService;

    /**
     * @param UpdateAddressByRelationService $updateAddressByRelationService
     * @param StoreAddressByRelationService $storeAddressByRelationService
     */
    public function __construct(
        UpdateAddressByRelationService $updateAddressByRelationService,
        StoreAddressByRelationService $storeAddressByRelationService
    )
    {
        $this->updateAddressByRelationService = $updateAddressByRelationService;
        $this->storeAddressByRelationService = $storeAddressByRelationService;
    }

    /**
     * @param  array  $data
     * @param Model $instance
     *
     * @return Address
     */
    public function run(array $data = [], $instance)
    {
        if($this->acceptInstance($instance)) {
            if($instance->address) {
                $this->updateAddressByRelationService->run($instance, $data);
            } else {
                $this->storeAddressByRelationService->run($data, $instance);
            }
        }
    }

      /**
     * @param Model $instance
     *
     * @return bool
     */
    private function acceptInstance($instance): bool
    {
        return $instance instanceof Order || $instance instanceof Client || $instance instanceof Employee || $instance instanceof Company;
    }
}
