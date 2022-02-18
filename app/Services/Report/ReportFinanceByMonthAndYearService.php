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
        $withOrder = [
            'order:id,client_id,number_of_installments',
            'order.client:id,name',
        ];

        $withExpense = [
            'expense:id,title',
        ];

        $installmentsPaid = Installment::typeOrder()->paidMonthly($year, $month)->with($withOrder)->get();
        $installmentsUnpaid = Installment::typeOrder()->unpaidMonthly($year, $month)->with($withOrder)->get();
        $installmentsToReceive = Installment::typeOrder()->toPendingMonthly($year, $month)->with($withOrder)->get();

        $installmentsExpensePaid = Installment::typeExpense()->paidMonthly($year, $month)->with($withExpense)->get();

        $amountPaid = (float) Installment::typeOrder()->paidMonthly($year, $month)->sum('value');
        $amountUnpaid = (float) Installment::typeOrder()->unpaidMonthly($year, $month)->sum('value');
        $amountToReceive = (float) Installment::typeOrder()->toPendingMonthly($year, $month)->sum('value');

        $expensePaid = (float) Installment::typeExpense()->paidMonthly($year, $month)->sum('value');
        // $expenseUnpaid = Installment::typeExpense()->unpaidMonthly($year, $month)->sum('value');
        // $expenseToPay = Installment::typeExpense()->toPendingMonthly($year, $month)->sum('value');

        $profit = $amountPaid - $expensePaid;

        return  [
            'month' => $month,
            'year' => $year,
            'month_name' => mb_strtoupper(Carbon::create($year, $month)->locale('pt-Br')->monthName, 'UTF-8'),
            'installments_paid' => $installmentsPaid,
            'installments_unpaid' => $installmentsUnpaid,
            'installments_to_receive' => $installmentsToReceive,
            'installments_expense_paid' => $installmentsExpensePaid,
            'amount_paid' => $amountPaid,
            'amount_unpaid' => $amountUnpaid,
            'amount_to_receive' => $amountToReceive,
            'expense_paid' => $expensePaid,
            'profit' => $profit
        ];
    }
}
