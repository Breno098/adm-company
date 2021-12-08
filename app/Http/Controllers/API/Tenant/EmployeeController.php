<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\Employee;
use App\Services\Employee\IndexService;
use App\Services\Employee\StoreService;
use App\Services\Employee\UpdateService;

class EmployeeController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('employee_index');

        $employees = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($employees, 'Employee retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('employee_add');

        $data = $request->validated();

        $employee = StoreService::run($data);

        return $this->sendResponse($employee, 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $this->authorize('employee_show');

        $employee->load([ 'addresses', 'contacts', 'user' ]);

        return $this->sendResponse($employee, 'Employee retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Employee $employee)
    {
        $this->authorize('employee_update');

        $data = $request->validated();

        $employee = UpdateService::run($employee, $data);

        return $this->sendResponse($employee, 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('employee_delete');

        $employee->delete();

        return $this->sendResponse([], 'Employee deleted successfully.');
    }

}
