<?php

namespace App\Http\Controllers\API;

use App\Services\Appointment\DestroyService;
use App\Services\Appointment\IndexActiveService;
use App\Services\Appointment\ShowService;
use App\Services\Appointment\StoreService;
use App\Services\Appointment\UpdateService;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', ['order.client', 'order.address']),
            $request->get('pagination', true),
            $request->get('itemsPerPage', 20),
        );

        return $this->sendResponse($appointments, 'Appointments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();

        $appointment = StoreService::run($data);

        return $this->sendResponse($appointment, 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = ShowService::run($id);

        return $this->sendResponse($appointment, 'Appointment retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\AppointmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request, Int $id)
    {
        $data = $request->validated();

        $appointment = UpdateService::run($id, $data);

        return $this->sendResponse($appointment, 'Appointment updated successfully.');
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

        return $this->sendResponse([], 'Appointment deleted successfully.');
    }
}
