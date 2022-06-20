<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class produtoController extends Controller
{
    private $produto;
    private $totalPage = 20;

    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    public function index()
    {
        $produtos = $this->produto->paginate($this->totalPage);

        return response()->json($produtos);
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

        $validate = validator($data, $this->produto->rules());

        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$insert = $this->produto->create($data) ) {
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
        if (!$produto = $this->produto->find($id)) {
            return response()->json(['error' => 'not_found']);
         }
         return response()->json($produto);
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
        $validate = validator($data, $this->produto->rules($id));
        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        if (!$produto = $this->produto->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$update = $produto->update($data)) {
            return response()->json(['error' => 'produto_not_updated'], 500);
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
        if (!$produto = $this->produto->find($id)) {
            return response()->json(['error' => 'not_found']);
        }

        if (!$delete = $produto->delete()) {
            return response()->json(['error' => 'produto_not_found']);
        }

        return response()->json($delete);
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $validate = validator($data, $this->produto->rulesSearch());
        if ($validate->fails()) {
           
            $messages = $validate->messages();
            return response()->json(['validate.error', $messages]);
        }

        $produtos = $this->produto->search($data, $this->totalPage);
        return response()->json($produtos);
       
    }
}
