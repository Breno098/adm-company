<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $apartament = rand(0, 5) === 1;

        return [
            'number' => $this->faker->buildingNumber,
            'district' => $this->faker->city,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'street' => $this->faker->streetName,
            'cep' => $this->faker->numerify('##.###-###'),
            'complement' => rand(0, 5) === 1 ? $this->faker->realText(rand(10, 100)) : null,
            'apartment' => $apartament ? $this->faker->numberBetween(10, 100) : null,
            'floor' => $apartament ? $this->faker->numberBetween(10, 5) : null,
            'description' => rand(0, 5) === 1 ? $this->faker->realText(rand(10, 100)) : null,
            'main' => true
        ];
    }
}
