<?php

namespace App\Services\Address;

use App\Models\Address;

class StoreAddressService
{
    /**
     * @param  array  $data
     *
     * @return Address
     */
    public function run(array $data = [])
    {
        return Address::create($data);
    }
}
