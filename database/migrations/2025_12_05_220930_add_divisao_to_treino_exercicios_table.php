<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('treino_exercicios', function (Blueprint $table) {
            // Adiciona a coluna divisao, permitindo nulo caso já existam dados
            // Colocamos after('exercicio_id') apenas para organização visual no banco
            $table->string('divisao', 1)->nullable()->after('exercicio_id'); 
        });
    }

    public function down(): void
    {
        Schema::table('treino_exercicios', function (Blueprint $table) {
            $table->dropColumn('divisao');
        });
    }
};
