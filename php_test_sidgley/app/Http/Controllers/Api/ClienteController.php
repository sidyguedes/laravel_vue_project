<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    private $cliente;
    private $totalPage = 20;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index()
    {
        $clientes = $this->cliente->paginate($this->totalPage);
        

        return response()->json($clientes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = validator($data, $this->cliente->rules());

        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$insert = $this->cliente->create($data) ) {
            return response()->json(['error' => 'error insert'], 500);
        }

        return response()->json($insert);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$cliente = $this->cliente->find($id)) {
            return response()->json(['error' => 'not_found']);
         }
         return response()->json($cliente);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   

        $data = $request->all();

        $validate = validator($data, $this->cliente->rules($id));
        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$cliente = $this->cliente->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$update = $cliente->update($data)) {
            return response()->json(['error' => 'cliente_not_updated'], 500);
        }

        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$cliente = $this->cliente->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$delete = $cliente->delete()) {
            return response()->json(['error' => 'cliente_not_found']);
        }

        return response()->json($delete);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $validate = validator($data, $this->cliente->rulesSearch());
        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        $clientes = $this->cliente->search($data, $this->totalPage);
        return response()->json($clientes);
       
    }
}
