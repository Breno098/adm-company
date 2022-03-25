<?php

namespace App\Services\Address;

use App\Models\Address;

class UpdateAddressService
{
    /**
     * @param Address $address
     * @param  array  $data
     *
     * @return Address
     */
    public function run(Address $address, array $data = [])
    {
        $address->update($data);

        return $address;
    }
}
