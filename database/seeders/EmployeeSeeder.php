<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Employee::factory(15)
            ->has(Address::factory()->count(1))
            ->has(Contact::factory()->count(2))
            ->create();
    }
}
