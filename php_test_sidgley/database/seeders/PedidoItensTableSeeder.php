<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PedidoItensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\PedidoItem::factory(15)->create();
    }
}
