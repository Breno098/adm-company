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
        Category::create([
            'name' => 'Periféricos',
            'icon' => 'mdi-wrench'
        ]);

        Category::create([
            'name' => 'Instalações',
            'icon' => 'mdi-pipe-disconnected'
        ]);
    }
}
