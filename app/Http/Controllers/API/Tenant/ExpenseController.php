<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Services\Expense\IndexExpenseService;
use App\Services\Expense\StoreExpenseService;
use App\Services\Expense\UpdateExpenseService;

class ExpenseController extends BaseApiController
{
    /**
     * @param Request  $request
     * @param IndexExpenseService $indexExpenseService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, IndexExpenseService $indexExpenseService)
    {
        $this->authorize('expense_index');

        $expenses = $indexExpenseService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($expenses, 'Expenses retrieved successfully.');
    }

    /**
     * @param StoreExpenseRequest $storeExpenseRequest
     * @param StoreExpenseService $storeExpenseService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreExpenseRequest $storeExpenseRequest, StoreExpenseService $storeExpenseService)
    {
        $this->authorize('expense_add');

        $data = $storeExpenseRequest->validated();

        $expense = $storeExpenseService->run($data);

        return $this->sendResponse($expense, 'Expense created successfully.');
    }

    /**
     * @param Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Expense $expense)
    {
        // $this->authorize('expense_show');

        $expense->load(['installments']);

        return $this->sendResponse($expense, 'Expense retrieved successfully.');
    }

    /**
     * @param UpdateExpenseRequest $updateExpenseRequest
     * @param UpdateExpenseService $updateExpenseService
     * @param Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        UpdateExpenseRequest $updateExpenseRequest,
        UpdateExpenseService $updateExpenseService,
        Expense $expense
    )
    {
        $this->authorize('expense_update');

        $data = $updateExpenseRequest->validated();

        $expense = $updateExpenseService->run($expense, $data);

        return $this->sendResponse($expense, 'Expense updated successfully.');
    }

    /**
     * @param Expense $expense
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('expense_delete');

        $expense->installments()->delete();

        $expense->delete();

        return $this->sendResponse([], 'Expense deleted successfully.');
    }

}
