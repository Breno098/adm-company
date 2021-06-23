<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = ['PJ', 'PF'];

        $type = Arr::random($types);

        return [
            'name' => $this->faker->name,
            'document' => $type === 'PF' ? $this->faker->numerify('###.###.###-##') : $this->faker->numerify('##.###.###/####-##'),
            'birth_date' => $this->faker->dateTimeBetween('-50 years', 'now'),
            'type' => $type,
            'notes' => rand(0, 1) === 1 ? $this->faker->realText(rand(10, 100)) : null,
            'fantasy_name' => rand(0, 5) === 1 ? $this->faker->name : null,
        ];
    }
}
