<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicoController;

// Rotas públicas (não requerem autenticação)
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (requerem autenticação)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rotas de Perfil
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::put('/user/password', [AuthController::class, 'updatePassword']);

    // Rotas de Agendamentos
    Route::get('/agendamentos/hoje', [AgendamentoController::class, 'hoje']);
    Route::apiResource('agendamentos', AgendamentoController::class);

    // Rotas de Clientes
    Route::apiResource('clientes', ClienteController::class);

    // Rotas de Serviços
    Route::apiResource('servicos', ServicoController::class);
});
