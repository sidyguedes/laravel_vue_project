<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomeCliente', 
        'sobrenomeCliente', 
        'cpf', 
        'email',
    ];

    public function rules($id = '') 
    {
        return [
            'nomeCliente' => "required|min:3|max:500|unique:clientes,nomeCliente,{$id},id",
            'sobrenomeCliente' => 'required|min:2',
            'cpf' => "required|min:11|unique:clientes,cpf,{$id},id",
            'email' => 'required',
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
            $this->where ('cpf', $data['key-search'])
                ->orWhere('nomeCliente', 'LIKE', "%{$data['key-search']}%")
                ->orWhere('sobrenomeCliente', 'LIKE', "%{$data['key-search']}%")
                ->orWhere('email', $data['key-search'])
                ->paginate($totalPage);

    }
}
