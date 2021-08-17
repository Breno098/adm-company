<?php

namespace App\Services\Client;

use App\Models\Client;

class DestroyService
{
    /**
     * @param  mixed  $id
     *
     * @return mixed
     */
    static public function run(Client $client)
    {
        $client->active = false;
        $client->save();
        
        return $client;
    }
}
