<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Breno Souza',
            'email' => 'brenohenrique098@gmail.com',
            'password' => Hash::make('defaultAdm098')
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('senhaParaAdmin@9876')
        ]);
    }
}
