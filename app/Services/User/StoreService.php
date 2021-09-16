<?php

namespace App\Services\User;

use App\Models\User;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        $user = User::create($data);

        return $user;
    }
}
