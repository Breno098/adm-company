<?php

namespace Database\Seeders;

use App\Models\ApplicationPreferences;
use Illuminate\Database\Seeder;

class ApplicationPreferncesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicationPreferences::create([
            'title' => 'Produtos',
            'first_title_option' => 'Produtos',
            'second_title_option' => 'Peças',
            'third_title_option' => 'Unidades',
            'fourth_title_option' => '',
            'fifth_title_option' => '',
            'route_name' => 'products.index',
            'params' => '',
            'link' => '',
            'icon' => '',
            'color' => '',
            'type' => 'menu'
        ]);
    }
}
