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
            'name' => 'Cancelado',
            'type' => 'order',
            'color' => 'orange accent-3'
        ]);

        Status::create([
            'name' => 'Aguardando aprovação',
            'type' => 'order',
            'color' => 'yellow accent-2'
        ]);

        Status::create([
            'name' => 'Em andamento',
            'type' => 'order',
            'color' => 'indigo'
        ]);

        Status::create([
            'name' => 'Aguardando pagamento',
            'type' => 'order',
            'color' => 'cyan darken-1'
        ]);

        Status::create([
            'name' => 'Concluido',
            'type' => 'order',
            'color' => 'green'
        ]);
    }
}
