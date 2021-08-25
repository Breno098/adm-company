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
            'name' => 'RESIDÊNCIA',
            'icon' => 'mdi-home-circle',
            'type' => 'client'
        ]);

        Category::create([
            'name' => 'EMPRESA',
            'icon' => 'mdi-home-modern',
            'type' => 'client'
        ]);

        Category::create([
            'name' => 'CONDOMÍNIO',
            'icon' => 'mdi-wall',
            'type' => 'client'
        ]);

        Category::create([
            'name' => 'OUTROS',
            'icon' => 'mdi-format-list-bulleted',
            'type' => 'client'
        ]);

        Category::create([
            'name' => 'PERIFÉRICOS',
            'icon' => 'mdi-wrench',
            'type' => 'product'
        ]);

        Category::create([
            'name' => 'INSTALAÇÃO',
            'icon' => 'mdi-pipe-disconnected',
            'type' => 'service'
        ]);

        Category::create([
            'name' => 'AJUDANTES',
            'icon' => 'mdi-account-multiple',
            'type' => 'expense'
        ]);

        Category::create([
            'name' => 'ALIMENTAÇÃO',
            'icon' => 'mdi-food',
            'type' => 'expense'
        ]);

        Category::create([
            'name' => 'FERRAMENTAS',
            'icon' => 'mdi-wrench',
            'type' => 'expense'
        ]);

        Category::create([
            'name' => 'IMPOSTOS',
            'icon' => 'mdi-cash-multiple',
            'type' => 'expense'
        ]);

        Category::create([
            'name' => 'MATERIAIS',
            'icon' => 'mdi-format-paint',
            'type' => 'expense'
        ]);

        Category::create([
            'name' => 'TRANSPORTE',
            'icon' => 'mdi-bus-side',
            'type' => 'expense'
        ]);

         Category::create([
            'name' => 'OUTROS',
            'icon' => 'mdi-format-list-bulleted',
            'type' => 'expense'
        ]);
    }
}
