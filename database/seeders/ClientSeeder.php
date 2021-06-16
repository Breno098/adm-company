<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $client_1 = Client::create([
            'name' => 'Breno Souza',
            'document' => '111.111.111-11',
            'fantasy_name' => 'Breno',
            'birth_date' => '1990-01-01',
            'type' => 'PF',
        ]);

        $client_1->addresses()->create([
            'number' => '150',
            'district' => 'Jd. Dom Pedro',
            'city' => 'Ribeirão Preto',
            'state' => 'São Paulo',
            'street' => 'R. das pedras',
            'cep' => '14000-11',
            'main' => true
        ]);

        $client_2 = Client::create([
            'name' => 'Enzo Henrique',
            'document' => '222.222.222-11',
            'fantasy_name' => 'Enzo Centro',
            'birth_date' => '2002-06-02',
            'type' => 'PF',
        ]);

        $client_2->addresses()->create([
            'number' => '350',
            'district' => 'Jd. das Flores',
            'city' => 'Franca',
            'state' => 'São Paulo',
            'street' => 'Av. dos Jardins',
            'cep' => '14111-11',
            'complement' => 'Ao lado do supermercado',
            'apartment' => '25',
            'floor' => 2,
            'main' => true
        ]);


        $client_3 = Client::create([
            'name' => 'Material de construção MATCONST LTDA',
            'document' => '00.000.000/0000-00',
            'fantasy_name' => 'MATCONST',
            'type' => 'PJ',
            'notes' => 'Material de construção perto a avenida principal.'
        ]);

        $client_3->addresses()->create([
            'number' => '1577',
            'district' => 'Pq. Indutrial',
            'city' => 'Ribeirão Preto',
            'state' => 'São Paulo',
            'street' => 'R. XV',
            'cep' => '14945-44',
            'main' => true
        ]);
    }
}
