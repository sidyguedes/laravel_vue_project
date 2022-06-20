<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idPedido' => $this->faker->numberBetween(1, 15),
            'idProduto' => $this->faker->numberBetween(1, 15),
            'numeroPedido' => $this->faker->numberBetween(1, 15),
            'quantidade' => $this->faker->numberBetween(1, 3),
        ];
    }
}
