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
            $status = Status::find($order->status_id)->name;

            $order->update([
                'status' => $status
            ]);

            $payment_status = 'EM ABERTO';

            if($status == 'CONCLUÍDO'){
                $payment_status = 'PAGO TOTAL';
            }

            if($status == 'CANCELADO'){
                $payment_status = 'CANCELADO';
            }

            $order->update([
                'payment_status' => $payment_status
            ]);
        });
    }
}
