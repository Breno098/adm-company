<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
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
        $client = Client::create($data);

        foreach ($data['contacts'] ?? [] as $contact) {
            $verifyContact = isset($contact['contact']);
            if($verifyContact){
                $client->contacts()->create($contact);
            }
        }

        foreach ($data['addresses'] ?? [] as $address) {
            $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
            if($verifyAddress){
                $client->addresses()->create($address);
            }
        }

        $client->category()->associate(Arr::get($data, 'category_id'));

        $client->save();

        return $client;
    }
}
