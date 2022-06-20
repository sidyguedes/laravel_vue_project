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
       //\App\Models\Cliente::factory(50)->create();
        //\App\Models\Pedido::factory(50)->create();
        //\App\Models\Produto::factory(50)->create();
        //\App\Models\PedidoItem::factory(50)->create();
    }
}
