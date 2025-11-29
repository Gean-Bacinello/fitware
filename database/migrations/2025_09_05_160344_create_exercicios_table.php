<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercicios', function (Blueprint $table) {
            $table->id(); // Corresponde a exercicioid
            $table->string('nome_exercicio', 150);
            $table->string('grupo_muscular', 100)->nullable();
            $table->string('divisao', 50)->nullable();
            $table->text('observacao')->nullable();
            $table->foreignId('usuario_criador_id')->nullable()->constrained('users');
            $table->enum('visibilidade', ['publico', 'privado'])->default('publico');
            $table->string('imagem_url')->nullable(); // Campo para imagem
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercicios');
    }
};