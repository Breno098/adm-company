<?php

namespace App\Services\Report;

use App\Models\Installment;
use Carbon\Carbon;

class ReportFinanceByMonthAndYearService
{
    /**
     * @param int $month
     * @param int $year
     *
     * @return array
     */
    public function run(int $month, int $year): array
    {
        $with = [
            'order:id,client_id,number_of_installments',
            'order.client:id,name'
        ];

        $installmentsPaid = Installment::paidMonthly($year, $month)->with($with)->get();
        $installmentsUnpaid = Installment::unpaidMonthly($year, $month)->with($with)->get();
        $installmentsToReceive = Installment::toReceiveMonthly($year, $month)->with($with)->get();
        $amountPaid = Installment::paidMonthly($year, $month)->sum('value');
        $amountUnpaid = Installment::unpaidMonthly($year, $month)->sum('value');
        $amountToReceive = Installment::toReceiveMonthly($year, $month)->sum('value');

        return  [
            'month' => $month,
            'year' => $year,
            'month_name' => mb_strtoupper(Carbon::create($year, $month)->locale('pt-Br')->monthName, 'UTF-8'),
            'installments_paid' => $installmentsPaid,
            'installments_unpaid' => $installmentsUnpaid,
            'installments_to_receive' => $installmentsToReceive,
            'amount_paid' => $amountPaid,
            'amount_unpaid' => $amountUnpaid,
            'amount_to_receive' => $amountToReceive
        ];
    }
}
