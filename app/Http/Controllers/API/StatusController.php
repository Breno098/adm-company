<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\StatusRequest;
use App\Models\Status;
use App\Services\Status\IndexActiveService;
use App\Services\Status\ShowService;
use App\Services\Status\StoreService;
use App\Services\Status\UpdateService;
use App\Services\Status\DestroyService;

class StatusController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $statuses = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('pagination', false),
            $request->get('itemsPerPage', 20),
        );

        return $this->sendResponse($statuses, 'Statuses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatusRequest $request)
    {
        $data = $request->validated();

        $status = StoreService::run($data);

        return $this->sendResponse($status, 'Status created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        $status = ShowService::run($status);

        return $this->sendResponse($status, 'Status retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatusRequest $request, Status $status)
    {
        $data = $request->validated();

        $status = UpdateService::run($status, $data);

        return $this->sendResponse($status, 'Status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        DestroyService::run($status);

        return $this->sendResponse([], 'Status deleted successfully.');
    }
}
