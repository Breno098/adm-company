<?php

namespace App\Services\Order;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        $order = Order::create($data);

        $order->client()->associate(Arr::get($data, 'client_id'));
        $order->status()->associate(Arr::get($data, 'status_id'));
        $order->address()->associate(Arr::get($data, 'address_id'));
        
        foreach ($data['products'] as $product) {
            if(!isset($product['id'])){
                continue;
            }

            if(!isset($product['value'])){
                $itemTypeProduct = Item::find($product['id']);
            }

            $order->products()->attach($product['id'], [
                'quantity'  => isset($product['quantity']) ? $product['quantity'] : 1,
                'value'     => isset($product['value']) ? $product['value'] : $itemTypeProduct->default_value
            ]);
        }

        foreach ($data['services'] as $service) {
            if(!isset($service['id'])){
                continue;
            }

            if(!isset($service['value'])){
                $itemTypeService = Item::find($service['id']);
            }

            $order->services()->attach($service['id'], [
                'quantity'  => isset($service['quantity']) ? $service['quantity'] : 1,
                'value'     => isset($service['value']) ? $service['value'] : $itemTypeService->default_value
            ]);
        }

        foreach ($data['payments'] as $payment) {
            if(!isset($payment['value'])){
                continue;
            }

            $order->payments()->attach($payment['id'], [
                'value' => $payment['value'],
                'date' => isset($payment['date']) ? $payment['date'] : null
            ]);
        }

        $order->save();

        return $order;
    }
}
