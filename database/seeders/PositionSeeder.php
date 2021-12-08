<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Status;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'name' => 'GERENTE',
        ]);

        Position::create([
            'name' => 'SECRETÁRIO(A)',
        ]);

        Position::create([
            'name' => 'DIRETOR(A) FINANCEIRO',
        ]);

        Position::create([
            'name' => 'AUX. FINANCEIRO',
        ]);

        Position::create([
            'name' => 'DIRETOR(A) ADMINISTRATIVO',
        ]);

        Position::create([
            'name' => 'AUX. ADMINISTRATIVO',
        ]);

        Position::create([
            'name' => 'ESTAGIÁRIO(A)',
        ]);

        Position::create([
            'name' => 'SUPERVISOR(A)',
        ]);

        Position::create([
            'name' => 'OPERADOR(A)',
        ]);

        Position::create([
            'name' => 'INSTALADOR(A)',
        ]);

        Position::create([
            'name' => 'AJUDANTE',
        ]);
    }
}
