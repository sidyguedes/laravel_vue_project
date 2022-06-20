<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $table = "pedido_itens";
    protected $timestamp = true;
    
    use HasFactory;

    protected $fillable = ['idPedido', 'idProduto', 'numeroPedido', 'quantidade', 'created_at', 'updated_at'];
}
