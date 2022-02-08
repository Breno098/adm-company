<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\Employee\IndexEmployeeService;
use App\Services\Employee\IndexService;
use App\Services\Employee\StoreEmployeeService;
use App\Services\Employee\StoreService;
use App\Services\Employee\UpdateEmployeeService;
use App\Services\Employee\UpdateService;

class EmployeeController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexEmployeeService $indexEmployeeService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexEmployeeService $indexEmployeeService)
    {
        $this->authorize('employee_index');

        $employees = $indexEmployeeService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($employees, 'Employee retrieved successfully.');
    }

    /**
     * @param StoreEmployeeRequest $storeEmployeeRequest
     * @param StoreEmployeeService $storeEmployeeService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $storeEmployeeRequest, StoreEmployeeService $storeEmployeeService)
    {
        $this->authorize('employee_add');

        $data = $storeEmployeeRequest->validated();

        $employee = $storeEmployeeService->run($data);

        return $this->sendResponse($employee, 'Employee created successfully.');
    }

    /**
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $this->authorize('employee_show');

        $employee->load([ 'addresses', 'contacts' ]);

        return $this->sendResponse($employee, 'Employee retrieved successfully.');
    }

    /**
     * @param UpdateEmployeeRequest $updateEmployeeRequest
     * @param UpdateEmployeeService $updateEmployeeService
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateEmployeeRequest $updateEmployeeRequest,
        UpdateEmployeeService $updateEmployeeService,
        Employee $employee
    )
    {
        $this->authorize('employee_update');

        $data = $updateEmployeeRequest->validated();

        $employee = $updateEmployeeService->run($employee, $data);

        return $this->sendResponse($employee, 'Employee updated successfully.');
    }

    /**
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
