<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Services\Appointment\DestroyService;
use App\Services\Appointment\IndexActiveService;
use App\Services\Appointment\ShowService;
use App\Services\Appointment\StoreService;
use App\Services\Appointment\UpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = IndexActiveService::run($request->all(), [
            'order.client', 'order.address'
        ], true);

        return $this->sendResponse($appointments, 'Appointments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
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
