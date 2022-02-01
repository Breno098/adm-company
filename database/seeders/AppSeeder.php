<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Order::all()->map(function($order){
            $order->update([
                'number_of_installments' => $order->installments()->count()
            ]);
        });
    }
}
