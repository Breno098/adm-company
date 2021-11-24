<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Appointment\IndexService;
use App\Services\Appointment\StoreService;
use App\Services\Appointment\UpdateService;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appointments = IndexService::run(
            $request->query(),
            $request->get('relations', ['client', 'address']),
            $request->get('itemsPerPage', false),
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
    public function show(Appointment $appointment)
    {
        $appointment->load(['order', 'client', 'address']);

        return $this->sendResponse($appointment, 'Appointment retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\AppointmentRequest  $request
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        $appointment = UpdateService::run($appointment, $data);

        return $this->sendResponse($appointment, 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return $this->sendResponse([], 'Appointment deleted successfully.');
    }
}