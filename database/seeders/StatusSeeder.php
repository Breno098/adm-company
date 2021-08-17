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
                'color' => 'orange accent-3'
            ], [
                'name' => 'Pendente',
                'description' => '',
                'type' => 'order',
                'color' => 'yellow accent-2'
            ], [
                'name' => 'Concluido',
                'description' => '',
                'type' => 'order',
                'color' => 'green'
            ], [
                'name' => 'Cancelado',
                'description' => 'Compromisso cancelado',
                'type' => 'appointment',
                'color' => 'orange accent-3'
            ], [
                'name' => 'Pendente',
                'description' => '',
                'type' => 'appointment',
                'color' => 'yellow accent-2'
            ], [
                'name' => 'Concluido',
                'description' => '',
                'type' => 'appointment',
                'color' => 'green'
            ], [
                'name' => 'Em atraso',
                'description' => '',
                'type' => 'appointment',
                'color' => 'red'
            ], [
                'name' => 'Estoque zerado',
                'description' => 'Quando a quantidade do item é igual a 0',
                'type' => 'item',
                'color' => 'red'
            ]
        ]);
    }
}
