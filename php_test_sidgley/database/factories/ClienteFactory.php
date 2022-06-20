<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
     /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nomeCliente' => $this->faker->firstName(),
            'sobrenomeCliente' => $this->faker->lastName(),
            'cpf' => $this->faker->numerify('###.###.###-##'),
            'email' => $this->faker->email(),
        ];
    }
}
