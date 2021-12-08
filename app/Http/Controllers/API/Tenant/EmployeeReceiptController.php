<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeReceipt\StoreRequest;
use App\Http\Requests\EmployeeReceipt\UpdateRequest;
use App\Models\Employee;
use App\Models\EmployeeReceipt;
use App\Services\EmployeeReceipt\DownloadPdfService;
use App\Services\EmployeeReceipt\StoreService;
use App\Services\EmployeeReceipt\UpdateService;
use App\Services\EmployeeReceipt\IndexService;
use App\Services\Image\LogoBase64;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;

class EmployeeReceiptController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('employee_receipt_index');

        $employeesReceipt = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($employeesReceipt, 'Employee Receipts retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('employee_receipt_add');

        $data = $request->validated();

        $employee = StoreService::run($data);

        return $this->sendResponse($employee, 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  EmployeeReceipt $employeeReceipt
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeReceipt $employeeReceipt)
    {
        $this->authorize('employee_receipt_show');

        $employeeReceipt->load([ 'employee' ]);

        return $this->sendResponse($employeeReceipt, 'Employee Receipt retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, EmployeeReceipt $employeeReceipt)
    {
        $this->authorize('employee_receipt_update');

        $data = $request->validated();

        $employee = UpdateService::run($employeeReceipt, $data);

        return $this->sendResponse($employee, 'Employee Receipt updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EmployeeReceipt $employeeReceipt
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
     * @param PDF $pdf
     * @param LogoBase64 $logoBase64
     */
    public function download(EmployeeReceipt $employeeReceipt, DownloadPdfService $downloadPdfService)
    {
        $this->authorize('employee_receipt_download');

        return $downloadPdfService->run($employeeReceipt);
    }
}
