<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeReceipt;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class EmployeeReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeReceipt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $employee = Employee::get()->random();

        return [
            'date_start' => now()->subDays(10),
            'date_end' => now()->addDays(10),
            'amount' => $employee->salary ?? $this->faker->randomFloat(2, 1500, 5000),
            'comments' => $this->faker->realText(rand(20, 100)),
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
        ];
    }
}
