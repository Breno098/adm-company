<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'Mouse',
            'type' => 'product',
            'default_value' => 35.00
        ]);

        Item::create([
            'name' => 'Monitor 20"',
            'type' => 'product',
            'default_value' => 350.99
        ]);

        Item::create([
            'name' => 'Teclado Membrana',
            'type' => 'product',
            'default_value' => 80.00
        ]);
    }
}
