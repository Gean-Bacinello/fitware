<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstrutorInformacoes extends Model
{
    

      /**
     * A tabela do banco de dados associada ao model.
     */
    protected $table = 'instrutor_informacoes';

    /**
     * A chave primária da tabela.
     */
    protected $primaryKey = 'user_id';


     /**
     * Indica se a chave primária é auto-incrementável.
     */
    public $incrementing = false;

    /**
     * Indica se o model deve ter timestamps (created_at e updated_at)
     */
    public $timestamps = false;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'user_id',
        'CREF',
        'telefone',
    ];

     /**
     * Define a relação inversa: esta informação pertence a um isntrutor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
