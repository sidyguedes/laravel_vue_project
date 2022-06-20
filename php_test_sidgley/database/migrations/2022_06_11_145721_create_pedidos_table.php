<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCliente');
            $table->foreign('idCliente')
                ->references('id')->on('clientes')->onDelete('cascade');
            $table->foreignId('idStatusPedido');
            $table->foreign('idStatusPedido')
                ->references('id')->on('status_pedidos')->onDelete('cascade');
            $table->dateTime('dtPedido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
