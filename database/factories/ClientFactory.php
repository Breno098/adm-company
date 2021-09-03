<?php

namespace Database\Factories;

use App\Models\Category;
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
        $category = Category::where('type', 'client')->get()->random();

        return [
            'name' => strtoupper($this->faker->name),
            'cnpj' => rand(0, 5) == 5 ? $this->faker->numerify('##.###.###/####-##') : null,
            'cpf' => rand(0, 5) == 5 ? $this->faker->numerify('###.###.###-##') : null,
            'birth_date' => $this->faker->dateTimeBetween('-80 years', 'now'),
            'notes' => rand(0, 1) === 1 ? strtoupper($this->faker->realText(rand(10, 100))) : null,
            'fantasy_name' => rand(0, 5) === 1 ? strtoupper($this->faker->name) : null,
            'category_id' => $category->id 
        ];
    }
}
