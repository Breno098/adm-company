<?php

namespace App\Services\Client;

use App\Models\Client;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Client $client, array $data = [])
    {
        $client->update($data);

        $client->contacts()->update(['active' => false]);
        $client->addresses()->update(['active' => false]);

        foreach ($data['contacts'] as $contact) {
            $id = isset($contact['id']) ? $contact['id'] : false;

            if($id){
                $contact['active'] = true;
                $client->contacts()->find($id)->update($contact);
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
                $address['active'] = true;
                $client->addresses()->find($id)->update($address);
            } else {
                $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
                if($verifyAddress){
                    $client->addresses()->create($address);
                }
            }
        }

        return $client;
    }
}
