<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'INSTALAÇÃO DE MÁQUINA',
            'type' => 'service',
            'default_value' => 100.99
        ]);

        Item::create([
            'name' => 'FORMATAÇÃO DE MÁQUINA',
            'type' => 'service',
            'default_value' => 80.00
        ]);
    }
}
