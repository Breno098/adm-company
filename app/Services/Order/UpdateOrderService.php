<?php

namespace App\Services\Order;

use App\Models\Item;
use App\Models\Order;
use App\Services\Installment\StoreInstallmentByOrderService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdateOrderService
{
    protected StoreInstallmentByOrderService $storeInstallmentByOrderService;

    /**
     * @param StoreInstallmentByOrderService $storeInstallmentByOrderService
     */
    public function __construct(StoreInstallmentByOrderService $storeInstallmentByOrderService)
    {
        $this->storeInstallmentByOrderService = $storeInstallmentByOrderService;
    }

    /**
     * @param Order $order
     * @param array  $data
     *
     * @return mixed
     */
    public function run(Order $order, array $data = [])
    {
        $order->update($data);

        $order->client()->associate(Arr::get($data, 'client_id'));
        $order->address()->associate(Arr::get($data, 'address_id'));

        $order->items()->sync([]);

        $this->updateProducts($data['products'], $order);
        $this->updateSevices($data['services'], $order);

        $order->save();

        $order->installments()->delete();

        foreach ($data['installments'] ?? [] as $installment) {
            $this->storeInstallmentByOrderService->run($installment, $order);
        }

        return $order;
    }

    private function updateProducts($products, Order $order)
    {
        foreach ($products ?? [] as $product) {
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

    private function updateSevices($services, Order $order)
    {
        foreach ($services ?? [] as $service) {
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
