<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Client::factory(15)
            ->has(Address::factory()->count(2))
            ->has(Contact::factory()->count(2))
            ->create();
    }
}
