<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return $this->sendResponse($orders, 'Orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $order = Order::create($input);

        foreach ($input['products'] as $product) {
            $item = Item::find($product['item']['id']);

            if($item && $product['quantity']){
                $order_item = OrderItem::create([
                    'quantity' => $product['quantity'],
                    'value' => $product['value']
                ]);

                $order_item->order()->associate($order);
                $order_item->item()->associate($item);
            }
        }

        // foreach ($input['services'] as $product) {
        //     $item = Item::find($product['item']['id']);

        //     if($item && $product['quantity']){
        //         $order_item = $order->items()->create([
        //             'quantity' => $product['quantity'],
        //             'value' => $product['value'] ? $product['value'] : $item->default_value
        //         ]);
        //         $order_item->item()->associate($item);
        //     }
        // }

        if($client_id = $input['client_id']){
            $client = Client::find($client_id);
            $order->client()->associate($client);
            $order->save();
        }

        if($status_id = $input['status_id']){
            $status = Status::find($status_id);
            $order->status()->associate($status);
            $order->save();
        }

        return $this->sendResponse($order, 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        return $this->sendResponse($order, 'Order retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:budget, sale, service, expense'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $order->name = $input['name'];
        $order->save();

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
