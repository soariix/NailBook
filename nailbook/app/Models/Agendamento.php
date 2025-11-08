<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agendamento extends Model
{
    protected $fillable = [
        'user_id',
        'cliente_id',
        'cliente_nome',
        'cliente_telefone',
        'servico_id',
        'servico',
        'data',
        'hora',
        'valor',
        'observacoes',
        'status'
    ];

    protected $casts = [
        'data' => 'date',
        'hora' => 'datetime:H:i',
        'valor' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }
}
