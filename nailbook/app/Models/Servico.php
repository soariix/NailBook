<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'nome',
        'preco',
        'duracao',
        'descricao'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'duracao' => 'integer'
    ];

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
