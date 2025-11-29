<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treinos', function (Blueprint $table) {
            $table->id(); // Corresponde a treinoid
            $table->string('nome_treino', 150);
            $table->text('descricao')->nullable();
            $table->foreignId('usuario_criador_id')->nullable()->constrained('users');
            $table->string('tipo', 30);
            $table->string('imagem_url')->nullable(); // Campo para imagem
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treinos');
    }
};