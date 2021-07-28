<?php

namespace App\Http\Controllers\API;

use App\Models\Address;
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
        $orders = Order::with([
            'client',
            'address',
        ])->paginate(1);

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

        $validator = Validator::make($input, [
            'client_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $order = Order::create($input);

        $products = [];
        foreach ($input['products'] as $product) {
            if(!$product['value']){
                $itemTypeProduct = Item::ofType('product')->find($product['item']['id']);
            }

            $products[] = [
                'item_id'   => $product['item']['id'],
                'quantity'  => $product['quantity'] ? $product['quantity'] : 1,
                'value'     => $product['value'] ? $product['value'] : $itemTypeProduct->default_value
            ];
        }
        $order->items()->sync($products);

        $services = [];
        foreach ($input['services'] as $service) {
            if(!$service['value']){
                $itemTypeService = Item::ofType('service')->find($product['item']['id']);
            }

            $services[] = [
                'item_id'   => $service['item']['id'],
                'quantity'  => $service['quantity'] ? $service['quantity'] : 1,
                'value'     => $service['value'] ? $service['value'] : $itemTypeService->default_value
            ];
        }
        $order->items()->sync($services);

        if($client_id = $input['client_id']){
            $client = Client::find($client_id);
            $order->client()->associate($client);
        }

        if($status_id = $input['status_id']){
            $status = Status::find($status_id);
            $order->status()->associate($status);
        }

        if($address_id = $input['address_id']){
            $address = Address::find($address_id);
            $order->address()->associate($address);
        }

        $order->save();

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

        $order->load([
            'client',
            'address',
            'products',
            'services'
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
    public function update(Request $request, Order $order)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'client_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $order->products()->sync([]);
        $order->services()->sync([]);

        $products = [];
        foreach ($input['products'] as $product) {
            if(!$product['value']){
                $itemTypeProduct = Item::ofType('product')->find($product['item']['id']);
            }

            $products[] = [
                'item_id'   => $product['item']['id'],
                'quantity'  => $product['quantity'] ? $product['quantity'] : 1,
                'value'     => $product['value'] ? $product['value'] : $itemTypeProduct->default_value
            ];
        }
        $order->products()->sync($products);

        $services = [];
        foreach ($input['services'] as $service) {
            if(!$service['value']){
                $itemTypeService = Item::ofType('service')->find($product['item']['id']);
            }

            $services[] = [
                'item_id'   => $service['item']['id'],
                'quantity'  => $service['quantity'] ? $service['quantity'] : 1,
                'value'     => $service['value'] ? $service['value'] : $itemTypeService->default_value
            ];
        }
        $order->services()->sync($services);

        if($client_id = $input['client_id']){
            $client = Client::find($client_id);
            $order->client()->associate($client);
        }

        if($status_id = $input['status_id']){
            $status = Status::find($status_id);
            $order->status()->associate($status);
        }

        if($address_id = $input['address_id']){
            $address = Address::find($address_id);
            $order->address()->associate($address);
        }

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
