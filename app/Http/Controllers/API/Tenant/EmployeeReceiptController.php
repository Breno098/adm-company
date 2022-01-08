<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeReceipt\StoreEmployeeReceiptRequest;
use App\Http\Requests\EmployeeReceipt\UpdateEmployeeReceiptRequest;
use App\Models\Employee;
use App\Models\EmployeeReceipt;
use App\Services\EmployeeReceipt\DownloadPdfEmployeeReceiptService;
use App\Services\EmployeeReceipt\IndexEmployeeReceiptService;
use App\Services\EmployeeReceipt\StoreEmployeeReceiptService;
use App\Services\EmployeeReceipt\UpdateEmployeeReceiptService;

class EmployeeReceiptController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexEmployeeReceiptService $indexEmployeeReceiptService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexEmployeeReceiptService $indexEmployeeReceiptService)
    {
        $this->authorize('employee_receipt_index');

        $employeesReceipt = $indexEmployeeReceiptService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($employeesReceipt, 'Employee Receipts retrieved successfully.');
    }

    /**
     * @param StoreEmployeeReceiptRequest $storeEmployeeReceiptRequest
     * @param StoreEmployeeReceiptService $storeEmployeeReceiptService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeReceiptRequest $storeEmployeeReceiptRequest, StoreEmployeeReceiptService $storeEmployeeReceiptService)
    {
        $this->authorize('employee_receipt_add');

        $data = $storeEmployeeReceiptRequest->validated();

        $employee = $storeEmployeeReceiptService->run($data);

        return $this->sendResponse($employee, 'Employee created successfully.');
    }

    /**
     * @param EmployeeReceipt $employeeReceipt
     *
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeReceipt $employeeReceipt)
    {
        $this->authorize('employee_receipt_show');

        $employeeReceipt->load([ 'employee' ]);

        return $this->sendResponse($employeeReceipt, 'Employee Receipt retrieved successfully.');
    }

    /**
     * @param UpdateEmployeeReceiptRequest $updateEmployeeReceiptRequest
     * @param UpdateEmployeeReceiptService $updateEmployeeReceiptService
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateEmployeeReceiptRequest $updateEmployeeReceiptRequest,
        UpdateEmployeeReceiptService $updateEmployeeReceiptService,
        EmployeeReceipt $employeeReceipt
    )
    {
        $this->authorize('employee_receipt_update');

        $data = $updateEmployeeReceiptRequest->validated();

        $employee = $updateEmployeeReceiptService->run($employeeReceipt, $data);

        return $this->sendResponse($employee, 'Employee Receipt updated successfully.');
    }

    /**
     * @param EmployeeReceipt $employeeReceipt
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeReceipt $employeeReceipt)
    {
        $this->authorize('employee_receipt_delete');

        $employeeReceipt->delete();

        return $this->sendResponse([], 'Employee Receipt deleted successfully.');
    }

    /**
     * @param EmployeeReceipt $employeeReceipt
     * @param DownloadPdfEmployeeReceiptService $downloadPdfEmployeeReceiptService
     *
     * @return \Illuminate\Http\Response
     */
    public function download(EmployeeReceipt $employeeReceipt, DownloadPdfEmployeeReceiptService $downloadPdfEmployeeReceiptService)
    {
        $this->authorize('employee_receipt_download');

        return $downloadPdfEmployeeReceiptService->run($employeeReceipt);
    }
}
