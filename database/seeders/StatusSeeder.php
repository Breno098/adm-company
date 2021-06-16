<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            [
                'name' => 'Cancelado',
                'description' => 'Pedido cancelado',
                'type' => 'order',
                'color' => '#3043cf'
            ], [
                'name' => 'Pendente',
                'description' => '',
                'type' => 'order',
                'color' => '#e38b19'
            ], [
                'name' => 'Concluido',
                'description' => '',
                'type' => 'order',
                'color' => '#2de319'
            ],  [
                'name' => 'Cancelado',
                'description' => 'Compromisso cancelado',
                'type' => 'appointment',
                'color' => '#3043cf'
            ], [
                'name' => 'Pendente',
                'description' => '',
                'type' => 'appointment',
                'color' => '#e38b19'
            ], [
                'name' => 'Concluido',
                'description' => '',
                'type' => 'appointment',
                'color' => '#2de319'
            ], [
                'name' => 'Em atraso',
                'description' => '',
                'type' => 'appointment',
                'color' => '#ff2200'
            ], [
                'name' => 'Estoque zerado',
                'description' => 'Quando a quantidade do item é igual a 0',
                'type' => 'item',
                'color' => '#cf7230'
            ]
        ]);
    }
}
