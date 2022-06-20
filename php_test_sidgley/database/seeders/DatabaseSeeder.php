<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
           //StatusPedidosTableSeeder::class,
           //UsersTableSeeder::class,
        ]);
        //\App\Models\User::factory(1)->create();
       //\App\Models\Cliente::factory(15)->create();
        //\App\Models\Pedido::factory(15)->create();
        \App\Models\Produto::factory(30)->create();
        //\App\Models\PedidoItem::factory(15)->create();
    }
}
