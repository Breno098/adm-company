<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Services\Client\DestroyService;
use App\Services\Client\IndexActiveService;
use App\Services\Client\StoreService;
use App\Services\Client\ShowService;
use App\Services\Client\UpdateService;

class ClientController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = IndexActiveService::run($request->all(), [
            'addresses'
        ]);

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
    public function show($id)
    {
        $client = ShowService::run($id, [ 'addresses', 'contacts' ]);

        return $this->sendResponse($client, 'Client retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ClientRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, Int $id)
    {
        $data = $request->validated();

        $client = UpdateService::run($id, $data);

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
        DestroyService::run($id);

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
