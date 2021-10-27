<?php

namespace App\Http\Controllers\API\Application;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Services\Service\IndexService;
use App\Services\Service\StoreService;
use App\Services\Service\UpdateService;

class ServiceController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('service_index');

        $item = IndexService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($item, 'Services retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $this->authorize('service_add');

        $data = $request->validated();

        $item = StoreService::run($data);

        return $this->sendResponse($item, 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $this->authorize('service_show');

        $service->load(['categories']);
        
        return $this->sendResponse($service, 'Service retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorize('service_update');

        $data = $request->validated();

        $service = UpdateService::run($service, $data);

        return $this->sendResponse($service, 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $this->authorize('service_delete');

        $service->delete();

        return $this->sendResponse([], 'Service deleted successfully.');
    }

}
