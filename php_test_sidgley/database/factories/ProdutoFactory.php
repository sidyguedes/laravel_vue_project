<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nomeProduto' => $this->faker->sentence($nbWords = 2, $variableNbWords = true),
            'valorUnitario' => $this->faker->randomFloat(2, 0, 10000),
            'codBarras' => $this->faker->isbn13(),
        ];
    }
}
