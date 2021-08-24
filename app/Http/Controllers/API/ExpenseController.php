<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\Expense\IndexActiveService;
use App\Services\Expense\StoreService;
use App\Services\Expense\UpdateService;

class ExpenseController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expenses = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($expenses, 'Expenses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        $data = $request->validated();

        $expense = StoreService::run($data);

        return $this->sendResponse($expense, 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense->load(['categories']);
        
        return $this->sendResponse($expense, 'Expense retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $data = $request->validated();

        $expense = UpdateService::run($expense, $data);

        return $this->sendResponse($expense, 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return $this->sendResponse([], 'Expense deleted successfully.');
    }

}
