<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\Order\DestroyService;
use App\Services\Order\IndexActiveService;
use App\Services\Order\ShowService;
use App\Services\Order\StoreService;
use App\Services\Order\UpdateService;
class OrderController extends BaseControllerApi
{
    /** 290 lines */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', ['client','address']),
            $request->get('pagination', true),
            $request->get('itemsPerPage', 10),
        );

        return $this->sendResponse($orders, 'Orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $data = $request->validated();

        $order = StoreService::run($data);

        $order->load([
            'client.contacts',
            'address',
            'products',
            'services',
            'payments'
        ])->append([
            'technical_visit_date',
            'technical_visit_hour'
        ]);

        return $this->sendResponse($order, 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load([
            'client.contacts',
            'address',
            'products',
            'services',
            'payments'
        ])->append([
            'technical_visit_date',
            'technical_visit_hour'
        ]);

        return $this->sendResponse($order, 'Order retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order = UpdateService::run($order, $data);

        $order->load([
            'client.contacts',
            'address',
            'products',
            'services',
            'payments'
        ])->append([
            'technical_visit_date',
            'technical_visit_hour'
        ]);

        return $this->sendResponse($order, 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return $this->sendResponse([], 'Order deleted successfully.');
    }
}
