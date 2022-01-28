<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Support\Arr;

class StoreClientService
{
    /**
     * @param  array  $data
     *
     * @return Client
     */
    public function run(array $data = []): Client
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
