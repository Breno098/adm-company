<?php

namespace App\Http\Controllers\API\Application;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Services\Client\CountService;
use App\Services\Client\IndexService;
use App\Services\Client\StoreService;
use App\Services\Client\UpdateService;

class ClientController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('client_index');

        $clients = IndexService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
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
        $this->authorize('client_add');

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
        $this->authorize('client_show');

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
        $this->authorize('client_update');

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
        $this->authorize('client_delete');

        $client->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }

    public function count(Request $request)
    {
        return $this->sendResponse([
            'count' => CountService::run($request->query()),
        ], 'Total count clients.');
    }
}
