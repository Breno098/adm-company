<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Services\Appointment\IndexAppointmentService;
use App\Services\Appointment\StoreAppointmentService;
use App\Services\Appointment\UpdateAppointmentService;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Appointment\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends BaseApiController
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param IndexAppointmentService $indexAppointmentService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexAppointmentService $indexAppointmentService)
    {
        $appointments = $indexAppointmentService->run(
            $request->query(),
            $request->get('relations', ['client', 'address']),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($appointments, 'Appointments retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAppointmentRequest $storeAppointmentRequest
     * @param  StoreAppointmentService $storeAppointmentService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppointmentRequest $storeAppointmentRequest, StoreAppointmentService $storeAppointmentService)
    {
        $data = $storeAppointmentRequest->validated();

        $appointment = $storeAppointmentService->run($data);

        return $this->sendResponse($appointment, 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['order', 'client', 'address']);

        return $this->sendResponse($appointment, 'Appointment retrieved successfully.');
    }

    /**
     * @param UpdateAppointmentRequest $updateAppointmentRequest
     * @param UpdateAppointmentService $updateAppointmentService
     * @param Appointment $appointment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateAppointmentRequest $updateAppointmentRequest,
        UpdateAppointmentService $updateAppointmentService,
        Appointment $appointment
    )
    {
        $data = $updateAppointmentRequest->validated();

        $appointment = $updateAppointmentService->run($appointment, $data);

        return $this->sendResponse($appointment, 'Appointment updated successfully.');
    }

    /**
     * @param Appointment $appointment
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return $this->sendResponse([], 'Appointment deleted successfully.');
    }
}
