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
            'name' => 'PIX',
        ]);

        Payment::create([
            'name' => 'DINHEIRO (À VISTA)',
        ]);

        Payment::create([
            'name' => 'CARTÃO DÉBITO',
        ]);

        Payment::create([
            'name' => 'CARTÃO CRÉDITO (À VISTA)',
        ]);

        Payment::create([
            'name' => 'CARTÃO CRÉDITO (2x) ',
        ]);

        Payment::create([
            'name' => 'CARTÃO CRÉDITO (3x) ',
        ]);

        Payment::create([
            'name' => 'CARTÃO CRÉDITO (4x) ',
        ]);

        Payment::create([
            'name' => 'CHEQUE',
        ]);
    }
}
