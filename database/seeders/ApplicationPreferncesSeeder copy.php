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
            'title' => 'ADM COMPANY',
            'type' => 'company-name',
        ]);

        ApplicationPreferences::create([
            'title' => 'RUA DAS PEDRAS',
            'type' => 'company-street',
        ]);

        ApplicationPreferences::create([
            'title' => 'RIBEIRÃO PRETO',
            'type' => 'company-city',
        ]);

        ApplicationPreferences::create([
            'title' => 'SP',
            'type' => 'company-state',
        ]);

        ApplicationPreferences::create([
            'title' => '999',
            'type' => 'company-number',
        ]);

        ApplicationPreferences::create([
            'title' => 'Inicio',
            'first_title_option' => 'Inicio',
            'second_title_option' => 'Home',
            'route_name' => 'home',
            'icon' => 'mdi-home',
            'color' => 'blue',
            'type' => 'menu',
            'order' => 1
        ]);

        ApplicationPreferences::create([
            'title' => 'Ordens',
            'first_title_option' => 'Ordens',
            'second_title_option' => 'Pedidos',
            'route_name' => 'order.index',
            'icon' => 'mdi-format-list-checks',
            'color' => 'purple',
            'type' => 'menu',
            'order' => 2
        ]);

        ApplicationPreferences::create([
            'title' => 'Agenda',
            'first_title_option' => 'Agenda',
            'second_title_option' => 'Compromissos',
            'route_name' => 'appointment.index',
            'icon' => 'mdi-calendar-today',
            'color' => 'red',
            'type' => 'menu',
            'order' => 3
        ]);

        ApplicationPreferences::create([
            'title' => 'Clientes',
            'first_title_option' => 'Clientes',
            'second_title_option' => 'Pessoas',
            'route_name' => 'client.index',
            'icon' => 'mdi-account',
            'color' => 'green',
            'type' => 'menu',
            'order' => 4
        ]);

        ApplicationPreferences::create([
            'title' => 'Produtos',
            'first_title_option' => 'Produtos',
            'second_title_option' => 'Peças',
            'third_title_option' => 'Unidades',
            'route_name' => 'products.index',
            'icon' => 'mdi-barcode',
            'color' => 'orange',
            'type' => 'menu',
            'order' => 5
        ]);

        ApplicationPreferences::create([
            'title' => 'Serviços',
            'first_title_option' => 'Serviços',
            'second_title_option' => 'Mão de Obra',
            'route_name' => 'service.index',
            'icon' => 'mdi-wrench',
            'color' => 'indigo',
            'type' => 'menu',
            'order' => 6
        ]);

        ApplicationPreferences::create([
            'title' => 'Categorias',
            'first_title_option' => 'Categorias',
            'route_name' => 'category.index',
            'icon' => 'mdi-format-list-bulleted-type',
            'color' => 'cyan accent-4',
            'type' => 'menu',
            'order' => 7
        ]);
    }
}
