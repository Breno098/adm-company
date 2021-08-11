<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where('active', true)
                        ->orderby('name')
                        ->get();

        return $this->sendResponse($clients, 'Clients retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:PF,PJ'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client = Client::create($input);

        foreach ($input['contacts'] as $contact) {
            $verifyContact = isset($contact['contact']);
            if($verifyContact){
                $client->contacts()->create($contact);
            }
        }

        foreach ($input['addresses'] as $address) {
            $verifyAddress = isset($address['street']) || isset($address['city']) || isset($address['cep']) || isset($address['apartment']);
            if($verifyAddress){
                $client->addresses()->create($address);
            }
        }

        return $this->sendResponse($client, 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::where('active', true)->where('id', $id)->first();

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->load(['addresses' => function($query) {
            $query->where('active', true);
        }, 'contacts' => function($query) {
            $query->where('active', true);
        }]);

        return $this->sendResponse($client, 'Client retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:PF,PJ'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client->update($input);

        $client->contacts()->update(['active' => false]);
        $client->addresses()->update(['active' => false]);

        foreach ($input['contacts'] as $contact) {
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

        foreach ($input['addresses'] as $address) {
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

        return $this->sendResponse($client, 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->active = false;
        $client->save();

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
