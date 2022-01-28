<?php

namespace App\Http\Controllers\API\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\Order\IndexOrderService;
use App\Services\Order\StoreOrderService;
use App\Services\Order\UpdateOrderService;
use App\Models\Order;


class OrderController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexOrderService $indexOrderService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexOrderService $indexOrderService)
    {
        $orders = $indexOrderService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($orders, 'Orders retrieved successfully.');
    }

    /**
     * @param StoreOrderRequest $storeOrderRequest
     * @param StoreOrderService $storeOrderService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreOrderRequest $storeOrderRequest,
        StoreOrderService $storeOrderService
    )
    {
        $data = $storeOrderRequest->validated();

        $order = $storeOrderService->run($data);

        $order->load([
            'client.contacts',
            'address',
            'products',
            'services',
            'payments',
            'installments'
        ]);

        return $this->sendResponse($order, 'Order created successfully.');
    }

    /**
     * @param Order $order
     *
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
            'installments'
        ]);

        return $this->sendResponse($order, 'Order retrieved successfully.');
    }

    /**
     * @param UpdateOrderRequest $updateOrderRequest
     * @param UpdateOrderService $updateOrderService
     * @param Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateOrderRequest $updateOrderRequest,
        UpdateOrderService $updateOrderService,
        Order $order
    )
    {
        $data = $updateOrderRequest->validated();

        $order = $updateOrderService->run($order, $data);

        $order->load([
            'client.contacts',
            'address',
            'products',
            'services',
            'payments',
            'installments'
        ]);

        return $this->sendResponse($order, 'Order updated successfully.');
    }

    /**
     * @param Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return $this->sendResponse([], 'Order deleted successfully.');
    }
}
