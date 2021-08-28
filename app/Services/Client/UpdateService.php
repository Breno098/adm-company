<?php

namespace App\Services\Client;

use App\Models\Client;
use Illuminate\Support\Arr;

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

        $client->contacts()->delete();
        $client->addresses()->delete();

        foreach ($data['contacts'] as $contact) {
            $id = isset($contact['id']) ? $contact['id'] : false;

            if($id){
                $client->contacts()->find($id)->restore();
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
                $client->addresses()->find($id)->restore();
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
