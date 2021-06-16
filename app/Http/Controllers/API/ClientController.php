<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;
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
        $clients = Client::where('active', true)->get();
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
            'type' => 'required|in:PF, PJ'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client = Client::create($input);

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
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        return $this->sendResponse($client, 'Client retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:PF, PJ'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client->name = $input['name'];
        $client->save();

        return $this->sendResponse($client, 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->active = false;
        $client->save();

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
