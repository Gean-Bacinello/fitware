<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteInformacoes extends Model
{
    use HasFactory;

    /**
     * A tabela do banco de dados associada ao model.
     */
    protected $table = 'cliente_informacoes';

    /**
     * A chave primária da tabela.
     */
    protected $primaryKey = 'user_id';

    /**
     * Indica se a chave primária é auto-incrementável.
     */
    public $incrementing = false;

    /**
     * Indica se o model deve ter timestamps (created_at e updated_at).
     */
    public $timestamps = false;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'user_id',
        'data_nascimento',
        'telefone',
        'sexo',
        'peso',
        'altura',
        'condicoes_medicas',
    ];

    /**
     * Define a relação inversa: esta informação pertence a um usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}