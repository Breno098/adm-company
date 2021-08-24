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
            'type' => 'company-infos',
            'content' => json_encode([
                'name' => 'ADM COMPANY',
                'contacts' => [
                    'phone' => '(16) 3636-3636',
                    'mobile' => '(16) 99999-9999',
                    'facebook' => '@adm-company'
                ],
                'address' => [
                    'street' => 'RUA DAS PEDRAS',
                    'city' => 'RIBEIRÃO PRETO',
                    'state' => 'SP',
                    'numer' => 999,
                    'district' => 'JARDIM DAS FLORES'
                ]
            ]),
        ]);

        ApplicationPreferences::create([
            'type' => 'service-order-infos',
            'content' => json_encode([
                'message' => 'MENSAGEM DE ORDEM DE SERVIÇO',
                'logo-path' => ''
            ]),
        ]);

        ApplicationPreferences::create([
            'type' => 'menu-items',
            'content' => json_encode([
                [
                    'title' => 'Inicio',
                    'first_title_option' => 'Inicio',
                    'second_title_option' => 'Home',
                    'route_name' => 'home',
                    'icon' => 'mdi-home',
                    'color' => 'blue',
                    'type' => 'menu',
                    'order' => 1
                ],
                [
                    'title' => 'Ordens',
                    'first_title_option' => 'Ordens',
                    'second_title_option' => 'Pedidos',
                    'route_name' => 'order.index',
                    'icon' => 'mdi-format-list-checks',
                    'color' => 'purple',
                    'type' => 'menu',
                    'order' => 2
                ],
                [
                    'title' => 'Agenda',
                    'first_title_option' => 'Agenda',
                    'second_title_option' => 'Compromissos',
                    'route_name' => 'appointment.index',
                    'icon' => 'mdi-calendar-today',
                    'color' => 'red',
                    'type' => 'menu',
                    'order' => 3
                ],
                [
                    'title' => 'Clientes',
                    'first_title_option' => 'Clientes',
                    'second_title_option' => 'Pessoas',
                    'route_name' => 'client.index',
                    'icon' => 'mdi-account',
                    'color' => 'green',
                    'type' => 'menu',
                    'order' => 4
                ],
                [
                    'title' => 'Serviços',
                    'first_title_option' => 'Serviços',
                    'second_title_option' => 'Mão de Obra',
                    'route_name' => 'service.index',
                    'icon' => 'mdi-wrench',
                    'color' => 'indigo',
                    'type' => 'menu',
                    'order' => 6
                ],
                [
                    'title' => 'Categorias',
                    'first_title_option' => 'Categorias',
                    'route_name' => 'category.index',
                    'icon' => 'mdi-format-list-bulleted-type',
                    'color' => 'cyan accent-4',
                    'type' => 'menu',
                    'order' => 7
                ]
            ]),
        ]);
    }
}
