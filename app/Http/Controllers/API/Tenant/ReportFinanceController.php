<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\ReportFinance\ByYearRequest;
use App\Services\Installment\GetInstallmentYearlyGroupByMonthService;

class ReportFinanceController extends BaseApiController
{
    public function byYear(ByYearRequest $byYearRequest, GetInstallmentYearlyGroupByMonthService $getInstallmentYearlyGroupByMonthService)
    {
        $data = $byYearRequest->validated();

        $result = $getInstallmentYearlyGroupByMonthService->run($data['year']);

        return $this->sendResponse($result);
    }
}
