<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Support\Arr;

class UpdateClientService
{
    /**
     * @param Client $client
     * @param array  $data
     *
     * @return Client
     */
    public function run(Client $client, array $data = []): Client
    {
        $client->update($data);

        $client->contacts()->delete();
        $client->addresses()->delete();

        foreach ($data['contacts'] as $contact) {
            $id = isset($contact['id']) ? $contact['id'] : false;

            if($id){
                $contactClient = $client->contacts()->withTrashed()->find($id);
                $contactClient->restore();
                $contactClient->update($contact);
            } else {
                $verifyContact = isset($contact['contact']);
                if($verifyContact){
                    $client->contacts()->create($contact);
                }
            }
        }

        foreach ($data['addresses'] as $address) {
            $id = isset($address['id']) ? $address['id'] : false;

            if($id){
                $addressClient = $client->addresses()->withTrashed()->find($id);
                $addressClient->restore();
                $addressClient->update($address);
            } else {
                $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
                if($verifyAddress){
                    $client->addresses()->create($address);
                }
            }
        }

        $client->category()->associate(Arr::get($data, 'category_id'));

        $client->save();

        return $client;
    }
}
