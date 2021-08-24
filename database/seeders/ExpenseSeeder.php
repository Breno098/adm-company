<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Expense::create([
            'title' => 'Folhas Sultfite A4',
            'date' => now(),
            'value' => 35.00,
        ]);

        Expense::create([
            'title' => 'Canetas',
            'date' => '2021-08-15',
            'value' => 5.00,
        ]);
    }
}
