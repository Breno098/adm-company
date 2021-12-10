<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\Expense\IndexService;
use App\Services\Expense\StoreService;
use App\Services\Expense\UpdateService;

class ExpenseController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorize('expense_index');

        $expenses = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($expenses, 'Expenses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExpenseRequest $request
     * @return Response
     */
    public function store(ExpenseRequest $request)
    {
        $this->authorize('expense_add');

        $data = $request->validated();

        $expense = StoreService::run($data);

        return $this->sendResponse($expense, 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param Expense $expense
     * @return Response
     */
    public function show(Expense $expense)
    {
        $this->authorize('expense_show');

        return $this->sendResponse($expense, 'Expense retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExpenseRequest $request
     * @param Expense $expense
     * @return Response
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->authorize('expense_update');

        $data = $request->validated();

        $expense = UpdateService::run($expense, $data);

        return $this->sendResponse($expense, 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Expense $expense
     * @return Response
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('expense_delete');

        $expense->delete();

        return $this->sendResponse([], 'Expense deleted successfully.');
    }

}
