<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json([
            'message' => 'Cliente cadastrado com sucesso!',
            'cliente' => $cliente
        ], 201);
    }

    public function show(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $cliente->update($request->all());

        return response()->json([
            'message' => 'Cliente atualizado com sucesso!',
            'cliente' => $cliente
        ]);
    }


    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'message' => 'Cliente removido com sucesso!'
        ]);
    }
}
