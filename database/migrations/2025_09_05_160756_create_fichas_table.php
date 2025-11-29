<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->id(); // Corresponde a fichaid
            $table->foreignId('cliente_id')->constrained('users');
            $table->foreignId('treino_id')->constrained('treinos');
            $table->foreignId('instrutor_id')->constrained('users');
            $table->date('data_atribuicao');
            $table->string('status_ficha', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas');
    }
};