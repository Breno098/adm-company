<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('local')) {
            $this->call([
                UserSeeder::class,
                RolesSeeder::class,
                CategorySeeder::class,
                StatusSeeder::class,
                ProductSeeder::class,
                ServiceSeeder::class,
                PaymentSeeder::class,
                ClientSeeder::class,
                ExpenseSeeder::class,
            ]);
        } 
    }
}
