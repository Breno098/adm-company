<?php

namespace App\Services\Address;

use Illuminate\Support\Arr;
use GuzzleHttp\Client;

class SearcheCepService
{
    /**
     * @param  mixed  $cep
     *
     * @return mixed
     */
    public function run($cep)
    {
        if(!$cep){
            return [];
        }

        try {
            $client = new Client();
            $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
            $response = json_decode($response->getBody());

            if(Arr::get($response, 'erro')){
                return [];
            }
            return $response;
        } catch (\Exception $e) {
            return [];
        }
    }
}
