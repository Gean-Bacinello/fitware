<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoEClienteAlvoToTreinosTable extends Migration
{
    public function up(): void
    {
        Schema::table('treinos', function (Blueprint $table) {
            // Alterar a coluna 'tipo' existente
            if (Schema::hasColumn('treinos', 'tipo')) {
                $table->enum('tipo', ['modelo', 'personalizado'])->default('modelo')->change();
            } else {
                $table->enum('tipo', ['modelo', 'personalizado'])->default('modelo')->after('usuario_criador_id');
            }

            // Adicionar a coluna 'cliente_alvo_id' apenas se não existir
            if (!Schema::hasColumn('treinos', 'cliente_alvo_id')) {
                $table->foreignId('cliente_alvo_id')->nullable()->constrained('users')->after('tipo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('treinos', function (Blueprint $table) {
            // Reverter a coluna 'tipo' para o estado anterior (ajuste conforme necessário)
            if (Schema::hasColumn('treinos', 'tipo')) {
                $table->enum('tipo', ['modelo'])->default('modelo')->change();
            }

            // Remover coluna cliente_alvo_id
            if (Schema::hasColumn('treinos', 'cliente_alvo_id')) {
                $table->dropForeign(['cliente_alvo_id']);
                $table->dropColumn('cliente_alvo_id');
            }
        });
    }
}
