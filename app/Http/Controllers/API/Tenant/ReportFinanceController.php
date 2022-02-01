<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\ReportFinance\ReportFinanceByMonthAndYearRequest;
use App\Http\Requests\ReportFinance\ReportFinanceByYearRequest;
use App\Models\Installment;
use App\Services\Report\ReportFinanceByMonthAndYearService;
use App\Services\Report\ReportFinanceByYearService;

class ReportFinanceController extends BaseApiController
{
    public function byYear(ReportFinanceByYearRequest $byYearRequest, ReportFinanceByYearService $reportFinanceByYearService)
    {
        $data = $byYearRequest->validated();

        $result = $reportFinanceByYearService->run($data['year']);

        return $this->sendResponse($result);
    }

    public function byMonthAndYear(
        ReportFinanceByMonthAndYearRequest $byMonthAndYearRequest,
        ReportFinanceByMonthAndYearService $reportFinanceByMonthAndYearService
    )
    {
        $data = $byMonthAndYearRequest->validated();

        $result = $reportFinanceByMonthAndYearService->run($data['month'], $data['year']);

        return $this->sendResponse($result);
    }
}
