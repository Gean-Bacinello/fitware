<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
     use HasFactory, Notifiable;
    /**
     * Os atributos que podem ser preenchidos em massa.
     *
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario', 
        'status',    
        'imagem_perfil_url',   
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     *
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtém os atributos que devem ser convertidos.
     *
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Obtém as informações pessoais associadas ao cliente (usuário).
     */
    public function clienteInformacoes(): HasOne
    {
        // Um usuário (que é um cliente) TEM UMA (hasOne) informação de cliente.
        // O Laravel vai procurar pela chave estrangeira 'user_id' na tabela 'cliente_informacoes'.
        return $this->hasOne(ClienteInformacoes::class, 'user_id');
    }

     /**
     * Obtém as informações pessoais associadas ao Instrutor (usuário).
     */
    public function InstrutorInformacoes(): HasOne
    {
        return $this->hasOne(InstrutorInformacoes::class, 'user_id');
    }


    // Relacionamentos refente a os Treinos

/**
 * Obtém as fichas de treino associadas a este usuário como cliente.
 */
public function fichasComoCliente()
{
    return $this->hasMany(Ficha::class, 'cliente_id');
}

/**
 * Obtém as fichas de treino que este usuário criou como instrutor.
 */
public function fichasComoInstrutor()
{
    return $this->hasMany(Ficha::class, 'instrutor_id');
}

/**
 * Obtém os treinos que este usuário criou.
 */
public function treinosCriados()
{
    return $this->hasMany(Treino::class, 'usuario_criador_id');
}
}
