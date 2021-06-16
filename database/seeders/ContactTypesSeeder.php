<?php

namespace Database\Seeders;

use App\Models\ContactType;
use Illuminate\Database\Seeder;

class ContactTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ContactType::insert([
            ['name' => 'Telefone'],
            ['name' => 'Celular'],
            ['name' => 'Email']
        ]);
    }
}
