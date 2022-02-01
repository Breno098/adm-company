<?php

namespace App\Services\Report;

use App\Models\Installment;

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

        return  [
            'installments_paid' => $installmentsPaid,
            'installments_unpaid' => $installmentsUnpaid,
            'installments_to_receive' => $installmentsToReceive
        ];
    }
}
