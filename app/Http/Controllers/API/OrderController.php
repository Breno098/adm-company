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
        ])->paginate(10);

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

        $input['technical_visit'] = $input['technical_visit_date'] . ' ' . $input['technical_visit_hour'];

        $order = Order::create($input);

        foreach ($input['products'] as $product) {
            if(!isset($product['id'])){
                continue;
            }

            if(!isset($product['value'])){
                $itemTypeProduct = Item::ofType('product')->find($product['id']);
            }

            $order->products()->attach($product['id'], [
                'quantity'  => isset($product['quantity']) ? $product['quantity'] : 1,
                'value'     => isset($product['value']) ? $product['value'] : $itemTypeProduct->default_value
            ]);
        }

        foreach ($input['services'] as $service) {
            if(!isset($service['id'])){
                continue;
            }

            if(!isset($service['value'])){
                $itemTypeService = Item::ofType('service')->find($service['id']);
            }

            $order->services()->attach($service['id'], [
                'quantity'  => isset($service['quantity']) ? $service['quantity'] : 1,
                'value'     => isset($service['value']) ? $service['value'] : $itemTypeService->default_value
            ]);
        }

        foreach ($input['payments'] as $payment) {
            if(!isset($payment['value'])){
                continue;
            }

            $order->payments()->attach($payment['id'], [
                'value'     => isset($payment['value']) ? $payment['value'] : 0
            ]);
        }


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
    public function update(Request $request, Int $id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'client_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['technical_visit'] = $input['technical_visit_date'] . ' ' . $input['technical_visit_hour'];

        $order->update($input);

        $order->products()->sync([]);
        $order->services()->sync([]);
        $order->payments()->sync([]);

        foreach ($input['products'] as $product) {
            if(!isset($product['id'])){
                continue;
            }

            if(!isset($product['value'])){
                $itemTypeProduct = Item::ofType('product')->find($product['id']);
            }

            $order->products()->attach($product['id'], [
                'quantity'  => isset($product['quantity']) ? $product['quantity'] : 1,
                'value'     => isset($product['value']) ? $product['value'] : $itemTypeProduct->default_value
            ]);
        }

        foreach ($input['services'] as $service) {
            if(!isset($service['id'])){
                continue;
            }

            if(!isset($service['value'])){
                $itemTypeService = Item::ofType('service')->find($service['id']);
            }

            $order->services()->attach($service['id'], [
                'quantity'  => isset($service['quantity']) ? $service['quantity'] : 1,
                'value'     => isset($service['value']) ? $service['value'] : $itemTypeService->default_value
            ]);
        }

        foreach ($input['payments'] as $payment) {
            if(!isset($payment['value'])){
                continue;
            }

            $order->payments()->attach($payment['id'], [
                'value'     => isset($payment['value']) ? $payment['value'] : 0
            ]);
        }

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
    public function destroy(Int $id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }

        $order->active = false;
        $order->save();

        return $this->sendResponse([], 'Order deleted successfully.');
    }
}
