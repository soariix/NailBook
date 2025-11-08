<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::orderBy('nome')->get();
        return response()->json($servicos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:1',
        ]);

        $servico = Servico::create($request->all());

        return response()->json([
            'message' => 'Serviço cadastrado com sucesso!',
            'servico' => $servico
        ], 201);
    }

    public function show(string $id)
    {
        $servico = Servico::findOrFail($id);
        return response()->json($servico);
    }


    public function update(Request $request, string $id)
    {
        $servico = Servico::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'duracao' => 'required|integer|min:1',
        ]);

        $servico->update($request->all());

        return response()->json([
            'message' => 'Serviço atualizado com sucesso!',
            'servico' => $servico
        ]);
    }

    public function destroy(string $id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();

        return response()->json([
            'message' => 'Serviço removido com sucesso!'
        ]);
    }
}
