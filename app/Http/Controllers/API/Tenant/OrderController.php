<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Services\Order\IndexService;
use App\Services\Order\StoreService;
use App\Services\Order\UpdateService;

class OrderController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
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
            'payments',
            'formOfPayments'
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
            'payments',
            'formOfPayments'
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
            'payments',
            'formOfPayments'
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
