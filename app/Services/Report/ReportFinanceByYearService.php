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
            $amountPaid = (float) Installment::typeOrder()->paidMonthly($year, $month)->sum('value');
            $amountUnpaid = (float) Installment::typeOrder()->unpaidMonthly($year, $month)->sum('value');
            $amountToReceive = (float) Installment::typeOrder()->toPendingMonthly($year, $month)->sum('value');
            $expensePaid = (float) Installment::typeExpense()->paidMonthly($year, $month)->sum('value');

            $profit = $amountPaid - $expensePaid;

            $report['monthly'][] = [
                'month' => $month,
                'month_name' => mb_strtoupper(Carbon::create($year, $month)->locale('pt-Br')->monthName, 'UTF-8'),
                'amount_paid' => $amountPaid,
                'amount_unpaid' => $amountUnpaid,
                'amount_to_receive' => $amountToReceive,
                'expense_paid' => $expensePaid,
                'profit' => $profit
            ];
        }

        $amountPaid = (float) Installment::typeOrder()->paidAnnually($year)->sum('value');
        $amountUnpaid = (float) Installment::typeOrder()->unpaidAnnually($year)->sum('value');
        $amountToReceive = (float) Installment::typeOrder()->toPendingAnnually($year)->sum('value');
        $expensePaid = (float) Installment::typeExpense()->paidAnnually($year)->sum('value');

        $profit = $amountPaid - $expensePaid;

        $report['annually'] =  [
            'year' => $year,
            'amount_paid' => $amountPaid,
            'amount_unpaid' => $amountUnpaid,
            'amount_to_receive' => $amountToReceive,
            'expense_paid' => $expensePaid,
            'profit' => $profit
        ];

        return $report;
    }
}
