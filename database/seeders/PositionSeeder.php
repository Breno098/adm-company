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
            'name' => 'AUX. FINANCEIRO',
        ]);
    }
}
