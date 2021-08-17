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
            'name' => 'Dinheiro (à vista)',
            'description' => 'Pagamento em dinheiro a vista',
        ]);

        Payment::create([
            'name' => 'Cartão de débito',
            'description' => 'Pagamento à vista no débito',
        ]);

        Payment::create([
            'name' => 'Cartão de crédito (à vista)',
            'description' => 'Pagamento à vista no crédito',
        ]);

        Payment::create([
            'name' => 'Cartão de crédito (2x) ',
            'description' => 'Pagamento em duas vezes no cartão de credito',
        ]);

        Payment::create([
            'name' => 'Cartão de crédito (3x) ',
            'description' => 'Pagamento em três vezes no cartão de credito',
        ]);

        Payment::create([
            'name' => 'Cartão de crédito (4x) ',
            'description' => 'Pagamento em quatro vezes no cartão de credito',
        ]);

        Payment::create([
            'name' => 'Cheque',
            'description' => 'Pagamento em cheque',
        ]);
    }
}
