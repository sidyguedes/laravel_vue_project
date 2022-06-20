<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            
        Schema::create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPedido');
            $table->foreign('idPedido')
                ->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreignId('idProduto');
            $table->foreign('idProduto')
                ->references('id')->on('produtos')->onDelete('cascade');
            $table->integer('numeroPedido');
            $table->integer('quantidade');
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
        Schema::dropIfExists('pedido_items');
    }
}
