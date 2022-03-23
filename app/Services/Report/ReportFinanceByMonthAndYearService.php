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

        $installmentsPaid = Installment::typeOrder()->paidMonthly($year, $month)->orderBy('pay_day')->with($withOrder)->get();
        $installmentsUnpaid = Installment::typeOrder()->unpaidMonthly($year, $month)->orderBy('due_date')->with($withOrder)->get();
        $installmentsToReceive = Installment::typeOrder()->toPendingMonthly($year, $month)->orderBy('due_date')->with($withOrder)->get();

        $installmentsExpensePaid = Installment::typeExpense()->paidMonthly($year, $month)->with($withExpense)->get();

        $amountPaid = (float) $installmentsPaid->sum('value');
        $amountUnpaid = (float) $installmentsUnpaid->sum('value');
        $amountToReceive = (float) $installmentsToReceive->sum('value');

        $expensePaid = (float) $installmentsExpensePaid->sum('value');
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
