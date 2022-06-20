<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomeProduto', 
        'valorUnitario', 
        'codBarras',
    ];

    public function rules($id = '') 
    {
        return [
            'nomeProduto' => "required|min:3|max:500|unique:produtos,nomeProduto,{$id},id",
            'valorUnitario' => 'required',
            'codBarras' => 'required|unique:produtos',
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
            $this->where('nomeProduto', 'LIKE', "%{$data['key-search']}%")
                ->orWhere('codBarras', $data['key-search'])
                ->paginate($totalPage);

    }
}
