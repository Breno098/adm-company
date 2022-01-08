<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\Request;
use App\Services\Client\IndexClientService;
use App\Services\Client\StoreClientService;
use App\Services\Client\UpdateClientService;
use App\Models\Client;

class ClientController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexClientService $indexClientService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexClientService $indexClientService)
    {
        $this->authorize('client_index');

        $clients = $indexClientService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($clients, 'Clients retrieved successfully.');
    }

    /**
     * @param StoreClientRequest $storeClientRequest
     * @param StoreClientService $storeClientService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $storeClientRequest, StoreClientService $storeClientService)
    {
        $this->authorize('client_add');

        $data = $storeClientRequest->validated();

        $client = $storeClientService->run($data);

        return $this->sendResponse($client, 'Client created successfully.');
    }

    /**
     * @param Client $client
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $this->authorize('client_show');

        $client->load([ 'addresses', 'contacts' ]);

        return $this->sendResponse($client, 'Client retrieved successfully.');
    }

    /**
     * @param UpdateClientRequest $updateClientRequest
     * @param UpdateClientService $updateClientService
     * @param Client $client
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateClientRequest $updateClientRequest,
        UpdateClientService $updateClientService,
        Client $client
    )
    {
        $this->authorize('client_update');

        $data = $updateClientRequest->validated();

        $client = $updateClientService->run($client, $data);

        return $this->sendResponse($client, 'Client updated successfully.');
    }

    /**
     * @param Client $client
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $this->authorize('client_delete');

        $client->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }

    /**
     * @param Request $request
     * @param IndexClientService $indexClientService
     *
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request, IndexClientService $indexClientService)
    {
        $clients = $indexClientService->run($request->query());

        return $this->sendResponse([
            'count' => $clients->count()
        ], 'Count clients.');
    }
}
