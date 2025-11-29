<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_treinos', function (Blueprint $table) {
            $table->id(); // Corresponde a execucaoid
            $table->foreignId('ficha_id')->constrained('fichas');
            $table->foreignId('exercicio_id')->constrained('exercicios');
            $table->string('repeticoes_feitas', 20)->nullable();
            $table->decimal('carga_kg', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_treinos');
    }
};