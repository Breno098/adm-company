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

class HomeController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function appointment(Request $request)
    {
        $appointments = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', [ 'order.client', 'order.address'])
        );

        return $this->sendResponse($appointments, 'Appointments retrieved successfully.');
    }
}
