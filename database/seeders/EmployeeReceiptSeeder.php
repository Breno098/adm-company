<?php

namespace Database\Seeders;

use App\Models\EmployeeReceipt;
use Illuminate\Database\Seeder;

class EmployeeReceiptSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        EmployeeReceipt::factory(3)->create();
    }
}
