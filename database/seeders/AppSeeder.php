<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AppSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Address::all()->map(function(Address $address){
            if($address->company_id) {
                $address->update([
                    'owner_id' => $address->company_id,
                    'owner_type' => 'Company'
                ]);
            }

            if($address->employee_id) {
                $address->update([
                    'owner_id' => $address->employee_id,
                    'owner_type' => 'Employee'
                ]);
            }

            if($address->client_id) {
                $address->update([
                    'owner_id' => $address->client_id,
                    'owner_type' => 'Client'
                ]);
            }
        });

        Order::all()->map(function(Order $order){
            $data = array_merge($order->address->toArray(), [
                'owner_id' => $order->id,
                'owner_type' => 'Order'
            ]);

            Address::create(Arr::except($data, ['id']));
        });
    }
}
