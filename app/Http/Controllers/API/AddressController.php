<?php

namespace App\Http\Controllers\API;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends BaseControllerApi
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::all();
        return $this->sendResponse($addresses, 'Addresses retrieved successfully.');
    }

    public function byClient($id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $addresses = $client->addresses;
        
        return $this->sendResponse($addresses, 'Addresses retrieved successfully.');

    }
}
