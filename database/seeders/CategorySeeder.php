<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [
                'name' => 'Serviços',
                'description' => 'Instalação/troca de peças.',
                'icon' => 'mdi-wrench'
            ],
            [
                'name' => 'Peças',
                'description' => null,
                'icon' => 'mdi-pipe-disconnected'
            ]
        ]);
    }
}
