<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ShowService
{
    /**
     * @param  mixed  $id
     * @param  array  $relations
     *
     * @return mixed
     */
    static public function run($id, array $relations = [])
    {
       $client = Client::find($id);

       if (is_null($client)) {
            return [];
        }

        $client->load($relations);
        
        return $client;
    }
}
