<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\Client\DestroyService;
use App\Services\Client\IndexService;
use App\Services\Client\StoreService;
use App\Services\Client\ShowService;
use App\Services\Client\UpdateService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ClientController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Gate::authorize('index_client');

        $clients = IndexService::run(
            $request->query(), 
            $request->get('relations', ['category']),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($clients, 'Clients retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $data = $request->validated();

        $client = StoreService::run($data);

        return $this->sendResponse($client, 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $client->load([ 'addresses', 'contacts' ]);

        return $this->sendResponse($client, 'Client retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ClientRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, Client $client)
    {
        $data = $request->validated();

        $client = UpdateService::run($client, $data);

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
        $client->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
