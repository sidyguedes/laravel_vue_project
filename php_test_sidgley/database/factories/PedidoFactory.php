<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idCliente' => $this->faker->numberBetween($min = 91, $max = 270),      
            'idStatusPedido' => $this->faker->numberBetween($min = 1, $max = 3),  
            'dtPedido' => $this->faker->dateTime($timezone = 'America/Sao_Paulo'),
        ];
    }
}
