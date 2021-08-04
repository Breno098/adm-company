<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Payment::create([
            'name' => 'Pix',
            'description' => 'Pagamento PIX',
        ]);

        Payment::create([
            'name' => '2x (vezes)',
            'description' => 'Pagamento em duas vezes',
        ]);

        Payment::create([
            'name' => 'Cheque',
            'description' => 'Pagamento em cheque',
        ]);
    }
}
