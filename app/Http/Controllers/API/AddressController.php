<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AddressController extends BaseControllerApi
{
    public function searchCep(Request $request)
    {
        $cep = $request->get('cep');

        if(!$cep){
            return $this->sendError('CEP not found', []);
        }

        try {
            $client = new Client();
            $response = $client->get("https://viacep.com.br/ws/{$cep}/json/");
            $response = json_decode($response->getBody());

            if(Arr::get($response, 'erro')){
                return $this->sendError('CEP not found', []);
            }
            return $this->sendResponse($response, 'CEP');
        } catch (\Exception $e) {
            return $this->sendError('CEP not found', []);
        }
    }
}
