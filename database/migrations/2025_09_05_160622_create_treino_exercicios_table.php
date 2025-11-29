<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treino_exercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treino_id')->constrained('treinos');
            $table->foreignId('exercicio_id')->constrained('exercicios');
            $table->string('series', 10)->nullable();
            $table->string('repeticoes', 10)->nullable();
            $table->string('carga', 20)->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treino_exercicios');
    }
};