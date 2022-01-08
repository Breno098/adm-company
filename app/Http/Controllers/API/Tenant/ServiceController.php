<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\Service\IndexServiceService;
use App\Services\Service\StoreServiceService;
use App\Services\Service\UpdateServiceService;

class ServiceController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexServiceService $indexServiceService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexServiceService $indexServiceService)
    {
        $this->authorize('service_index');

        $item = $indexServiceService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($item, 'Services retrieved successfully.');
    }

    /**
     * @param StoreServiceRequest $storeServiceRequest
     * @param StoreServiceService $storeServiceService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $storeServiceRequest, StoreServiceService $storeServiceService)
    {
        $this->authorize('service_add');

        $data = $storeServiceRequest->validated();

        $item = $storeServiceService->run($data);

        return $this->sendResponse($item, 'Service created successfully.');
    }

    /**
     * @param Service $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $this->authorize('service_show');

        $service->load(['categories']);

        return $this->sendResponse($service, 'Service retrieved successfully.');
    }

    /**
     * @param UpdateServiceRequest $updateServiceRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateServiceRequest $updateServiceRequest,
        UpdateServiceService $updateServiceService,
        Service $service
    )
    {
        $this->authorize('service_update');

        $data = $updateServiceRequest->validated();

        $service = $updateServiceService->run($service, $data);

        return $this->sendResponse($service, 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $this->authorize('service_delete');

        $service->delete();

        return $this->sendResponse([], 'Service deleted successfully.');
    }

}
