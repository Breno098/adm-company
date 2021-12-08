<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dependUser = rand(0,20) == 1;
        $user = $dependUser ? User::get()->random() : null;

        $position = Position::get()->random();

        return [
            'name' => $dependUser ? strtoupper($user->name) : strtoupper($this->faker->name),
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'rg' => $this->faker->numerify('##.###.###-#'),
            'birth_date' => $this->faker->dateTimeBetween('-80 years', 'now'),
            'admission_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'resignation_date' => rand(0,10) == 1 ? $this->faker->dateTimeBetween('-2 years', 'now') : null,
            'salary' => $this->faker->randomFloat(2, 1500, 5000),
            'user_id' => $dependUser ? $user->id : null,
            'position_id' => $position->id
        ];
    }
}
