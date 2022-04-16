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
        Category::updateOrCreate([
            'name' => 'RESIDÊNCIA',
            'icon' => 'mdi-home-circle',
            'type' => 'client'
        ]);

        Category::updateOrCreate([
            'name' => 'EMPRESA',
            'icon' => 'mdi-home-modern',
            'type' => 'client'
        ]);

        Category::updateOrCreate([
            'name' => 'CONDOMÍNIO',
            'icon' => 'mdi-wall',
            'type' => 'client'
        ]);

        Category::updateOrCreate([
            'name' => 'OUTROS',
            'icon' => 'mdi-format-list-bulleted',
            'type' => 'client'
        ]);

        Category::updateOrCreate([
            'name' => 'HIDRÁULICA',
            'icon' => 'mdi-wrench',
            'type' => 'product'
        ]);

        Category::updateOrCreate([
            'name' => 'INSTALAÇÃO',
            'icon' => 'mdi-pipe-disconnected',
            'type' => 'service'
        ]);

        //
        Category::updateOrCreate([
            'name' => 'MANUTENÇÃO',
            'icon' => 'mdi-wrench',
            'type' => 'service'
        ]);

        Category::updateOrCreate([
            'name' => 'REPARO',
            'icon' => 'mdi-wrench',
            'type' => 'service'
        ]);

        Category::updateOrCreate([
            'name' => 'HIGIENIZAÇÃO',
            'icon' => 'mdi-alert-octagram',
            'type' => 'service'
        ]);
        //

        Category::updateOrCreate([
            'name' => 'AJUDANTES',
            'icon' => 'mdi-account-multiple',
            'type' => 'expense'
        ]);

        Category::updateOrCreate([
            'name' => 'ALIMENTAÇÃO',
            'icon' => 'mdi-food',
            'type' => 'expense'
        ]);

        Category::updateOrCreate([
            'name' => 'FERRAMENTAS',
            'icon' => 'mdi-wrench',
            'type' => 'expense'
        ]);

        Category::updateOrCreate([
            'name' => 'IMPOSTOS',
            'icon' => 'mdi-cash-multiple',
            'type' => 'expense'
        ]);

        Category::updateOrCreate([
            'name' => 'MATERIAIS',
            'icon' => 'mdi-format-paint',
            'type' => 'expense'
        ]);

        Category::updateOrCreate([
            'name' => 'TRANSPORTE',
            'icon' => 'mdi-bus-side',
            'type' => 'expense'
        ]);

         Category::updateOrCreate([
            'name' => 'OUTROS',
            'icon' => 'mdi-format-list-bulleted',
            'type' => 'expense'
        ]);
    }
}
