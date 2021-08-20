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
        Status::create([
            'name' => 'CANCELADO',
            'type' => 'order',
            'color' => 'orange accent-3'
        ]);

        Status::create([
            'name' => 'AGUARDANDO APROVAÇÃO',
            'type' => 'order',
            'color' => 'yellow accent-2'
        ]);

        Status::create([
            'name' => 'EM ANDAMENTO',
            'type' => 'order',
            'color' => 'indigo'
        ]);

        Status::create([
            'name' => 'AGUARDANDO PAGAMENTO',
            'type' => 'order',
            'color' => 'cyan darken-1'
        ]);

        Status::create([
            'name' => 'CONCLUÍDO',
            'type' => 'order',
            'color' => 'green'
        ]);
    }
}
