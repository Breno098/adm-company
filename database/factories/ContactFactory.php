<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = Arr::random([
            'Email',
            'Celular',
            'WhatsApp',
            'Outros'
        ]);

        return [
            'contact' => $type === 'Email' ? $this->faker->email : $this->faker->numerify('(##) #####-###'),
            'type' => $type,
            'main' => true,
        ];
    }
}
