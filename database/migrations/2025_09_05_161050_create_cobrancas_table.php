<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->id(); // Corresponde a cobrancaid
            $table->foreignId('matricula_id')->constrained('matriculas');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->string('status_cobranca', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobrancas');
    }
};