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
    static public function run($id)
    {
       $client = Client::find($id);

        if (is_null($client)) {
            return [];
        }

        $client->active = false;
        $client->save();
        
        return $client;
    }
}
