<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_exercicio',
        'grupo_muscular',
        'divisao',
        'usuario_criador_id',
        'visibilidade',
         'imagem_url'  
    ];


    /**
     * Obtém o usuário que criou o exercício.
     */
    public function criador()
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }

     /**
     * Obtém os treinos que contêm este exercício.
     */
    public function treinos()
    {
        return $this->belongsToMany(Treino::class, 'treino_exercicios', 'exercicio_id', 'treino_id')
                    ->withPivot('series', 'repeticoes', 'carga', 'observacoes');
    }
}