<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Contact;
use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        PaymentType::create([
            'name' => 'Pix',
            'description' => 'Pagamento PIX',
        ]);

        PaymentType::create([
            'name' => '2x (vezes)',
            'description' => 'Pagamento em duas vezes',
        ]);

        PaymentType::create([
            'name' => 'Cheque',
            'description' => 'Pagamento em cheque',
        ]);
    }
}
