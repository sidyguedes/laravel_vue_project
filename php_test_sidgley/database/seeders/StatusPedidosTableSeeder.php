<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\StatusPedido;

class StatusPedidosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('status_pedidos')->delete();

        $statusPedido = [
            ['id' => '1', 'statusPedido' => 'Em Aberto'],
            ['id' => '2', 'statusPedido' => 'Pago'],
            ['id' => '3', 'statusPedido' => 'Cancelado'],
        ];

        foreach($statusPedido as $status){
            StatusPedido::create($status);
        }

        StatusPedido::insert($status);

    }
}
