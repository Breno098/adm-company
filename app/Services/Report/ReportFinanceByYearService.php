<?php

namespace App\Services\Report;

use App\Models\Installment;
use Carbon\Carbon;

class ReportFinanceByYearService
{
    const MONTHS_NUMBER = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    /**
     * @param int $year
     *
     * @return array
     */
    public function run(int $year): array
    {
        $report = [];
        foreach (self::MONTHS_NUMBER as $month) {
            $report['monthly'][] = [
                'month' => $month,
                'month_name' => mb_strtoupper(Carbon::create($year, $month)->locale('pt-Br')->monthName, 'UTF-8'),
                'amount_paid' => Installment::paidMonthly($year, $month)->sum('value'),
                'amount_unpaid' => Installment::unpaidMonthly($year, $month)->sum('value'),
                'amount_to_receive' => Installment::toReceiveMonthly($year, $month)->sum('value'),
            ];
        }

        $report['annually'] =  [
            'year' => $year,
            'amount_paid' => Installment::paidAnnually($year)->sum('value'),
            'amount_unpaid' => Installment::unpaidAnnually($year)->sum('value'),
            'amount_to_receive' => Installment::toReceiveAnnually($year)->sum('value'),
        ];

        return $report;
    }
}
