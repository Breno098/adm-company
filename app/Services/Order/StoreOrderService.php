<?php

namespace App\Services\Order;

use App\Models\Item;
use App\Models\Order;
use App\Services\Address\StoreAddressByRelationService;
use App\Services\Installment\StoreInstallmentByOrderService;
use Illuminate\Support\Arr;

class StoreOrderService
{
    protected StoreInstallmentByOrderService $storeInstallmentByOrderService;

    protected StoreAddressByRelationService $storeAddressByRelationService;

    /**
     * @param StoreInstallmentByOrderService $storeInstallmentByOrderService
     * @param StoreAddressByRelationService $storeAddressByRelationService
     */
    public function __construct(
        StoreInstallmentByOrderService $storeInstallmentByOrderService,
        StoreAddressByRelationService $storeAddressByRelationService
    )
    {
        $this->storeInstallmentByOrderService = $storeInstallmentByOrderService;
        $this->storeAddressByRelationService = $storeAddressByRelationService;
    }

    /**
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(array $data = [])
    {
        $order = Order::create($data);

        $order->client()->associate(Arr::get($data, 'client_id'));

        $order->technician()->associate(Arr::get($data, 'technician_id'));

        $this->storeAddressByRelationService->run($data['address'], $order);

        $this->insertItems($data, $order);

        foreach ($data['installments'] ?? [] as $installment) {
            $this->storeInstallmentByOrderService->run($installment, $order);
        }

        return $order;
    }

    /**
     * @param array $data
     * @param Order $order
     *
     * @return bool
     */
    private function insertItems(array $data = [], Order $order)
    {
        $this->insertProducts($data['products'] ?? [], $order);
        $this->insertServices($data['services'] ?? [], $order);

        return $order->save();
    }

    private function insertProducts($products, Order $order){
        foreach ($products as $product) {
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
    }

    private function insertServices($services, Order $order){
        foreach ($services as $service) {
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
    }


}
