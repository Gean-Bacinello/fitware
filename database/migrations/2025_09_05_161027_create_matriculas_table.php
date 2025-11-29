<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id(); // Corresponde a matriculaid
            $table->foreignId('cliente_id')->constrained('users');
            $table->foreignId('plano_id')->constrained('planos');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('status_matricula', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};