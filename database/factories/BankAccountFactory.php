<?php

namespace Database\Factories;

use App\Models\BankAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class BankAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BankAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bank = Arr::random([
            'Itáu',
            'Santander',
            'Bradesco',
            'NuBank',
            'Outro'
        ]);

        return [
            'number' => $this->faker->numerify('#####-#'),
            'agency' => $this->faker->numerify('####'),
            'bank' => $bank,
        ];
    }
}
