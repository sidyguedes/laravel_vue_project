<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoItem;


class pedidoController extends Controller
{
    private $pedido;
    private $totalPage = 20;

    public function __construct(Pedido $pedido, PedidoItem $pedidoItem)
    {
        $this->pedido = $pedido;
        $this->pedidoItem = $pedidoItem;
    }

    public function index()
    {

        $pedidos = $this->pedido->with('itens.produto', 'cliente', 'status')->paginate($this->totalPage);

        return response()->json($pedidos);
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

        $validate = validator($data, $this->pedido->rules());

        if ($validate->fails()) {

            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$insert = $this->pedido->create($data) ) {

            return response()->json(['error' => 'error insert'], 500);
        }


        foreach ($data['itens'] as $item) {
            $itens[] = [
                'idPedido' => $insert['id'],
                'idProduto' => $item['idProduto'],
                'numeroPedido' => $item['numeroPedido'],
                'quantidade' => $item['quantidade'],

            ];
        }

        PedidoItem::insert($itens);


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
        if (!$pedido = $this->pedido->find($id)) {
            return response()->json(['error' => 'not_found']);
        }
        return response()->json($pedido);
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
        $data = $request()->all();

        $validate = validator($data, $this->pedido->rules($id));
        if ($validate->fails()) {

            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$pedido = $this->pedido->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$update = $pedido->update($data)) {
            return response()->json(['error' => 'pedido_not_updated'], 500);
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
        if (!$pedido = $this->pedido->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$delete = $pedido->delete()) {
            return response()->json(['error' => 'pedido_not_found']);
        }

        return response()->json($delete);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $validate = validator($data, $this->pedido->rulesSearch());
        if ($validate->fails()) {

            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        $pedidos = $this->pedido->search($data, $this->totalPage);
        return response()->json($pedidos);

    }
}
