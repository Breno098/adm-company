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
            'name' => 'PERIFÉRICOS',
            'icon' => 'mdi-wrench'
        ]);

        Category::create([
            'name' => 'INSTALAÇÃO',
            'icon' => 'mdi-pipe-disconnected'
        ]);
    }
}
