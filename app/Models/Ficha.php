<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'cliente_id',
        'treino_id',
        'instrutor_id',
        'data_atribuicao',
        'status_ficha',
    ];

    protected $casts = [
    'data_atribuicao' => 'datetime',
];

    /**
     * A ficha pertence a um cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    /**
     * A ficha foi criada por um instrutor.
     */
    public function instrutor()
    {
        return $this->belongsTo(User::class, 'instrutor_id');
    }

    /**
     * A ficha está associada a um treino.
     */
    public function treino()
    {
        return $this->belongsTo(Treino::class, 'treino_id');
    }
}