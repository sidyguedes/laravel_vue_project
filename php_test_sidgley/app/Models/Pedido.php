<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'idCliente',
        'idStatusPedido',
        'dtPedido'
    ];

    public function rules($id = '')
    {
        return [
            'idCliente' => "required",
            'dtPedido' => 'required',

        ];

    }

    public function rulesSearch($id = '')
    {
        return [
            'key-search' => 'required'
        ];

    }

    public function search($data, $totalPage)
    {
        return
            $this->where ('dtPedido', $data['key-search'])
                ->paginate($totalPage);

    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class, 'idPedido', 'id');

    }

    public function cliente()
    {

        return $this->belongsTo(Cliente::class, 'idCliente', 'id');

    }

    public function status()
    {

        return $this->belongsTo(StatusPedido::class, 'idStatusPedido', 'id');

    }

}
