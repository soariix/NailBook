<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    public function index()
    {
        $agendamentos = Agendamento::where('user_id', Auth::id())
            ->orderBy('data', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return response()->json($agendamentos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_nome' => 'required|string|max:255',
            'cliente_telefone' => 'nullable|string|max:255',
            'servico' => 'required|string|max:255',
            'data' => 'required|date',
            'hora' => 'required',
            'valor' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
            'status' => 'nullable|in:pendente,confirmado,concluido,cancelado'
        ]);

        $validated['user_id'] = Auth::id();

        $agendamento = Agendamento::create($validated);

        return response()->json([
            'message' => 'Agendamento criado com sucesso!',
            'agendamento' => $agendamento
        ], 201);
    }

    public function show($id)
    {
        $agendamento = Agendamento::where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($agendamento);
    }

    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::where('user_id', Auth::id())
            ->findOrFail($id);

        $validated = $request->validate([
            'cliente_nome' => 'sometimes|string|max:255',
            'cliente_telefone' => 'nullable|string|max:255',
            'servico' => 'sometimes|string|max:255',
            'data' => 'sometimes|date',
            'hora' => 'sometimes',
            'valor' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
            'status' => 'sometimes|in:pendente,confirmado,concluido,cancelado'
        ]);

        $agendamento->update($validated);

        return response()->json([
            'message' => 'Agendamento atualizado com sucesso!',
            'agendamento' => $agendamento
        ]);
    }

    public function destroy($id)
    {
        $agendamento = Agendamento::where('user_id', Auth::id())
            ->findOrFail($id);

        $agendamento->delete();

        return response()->json([
            'message' => 'Agendamento deletado com sucesso!'
        ]);
    }

    public function hoje()
    {
        $agendamentos = Agendamento::where('user_id', Auth::id())
            ->whereDate('data', today())
            ->orderBy('hora')
            ->get();

        return response()->json($agendamentos);
    }
}
