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
            'email' => 'breno@email.com',
            'password' => Hash::make('123456')
        ]);

        User::factory(15)->create();
    }
}
