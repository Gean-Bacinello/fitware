<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treino extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_treino',
        'descricao',
        'usuario_criador_id',
        'tipo',
        'cliente_alvo_id',
        'imagem_url'
    ];

    /**
     * Um treino é criado por um usuário (instrutor).
     */
    public function criador()
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }

    /**
     * Um treino pertence a um cliente alvo (se for personalizado).
     */
    public function clienteAlvo()
    {
        return $this->belongsTo(User::class, 'cliente_alvo_id');
    }

    /**
     * Um treino possui muitos exercícios.
     */
    public function exercicios()
    {
        // Usa a tabela pivo 'treino_exercicios'
        return $this->belongsToMany(Exercicio::class, 'treino_exercicios')
                    ->withPivot('series', 'repeticoes', 'carga', 'observacoes', 'divisao'); // Importante para acessar os dados da ficha
    }
}