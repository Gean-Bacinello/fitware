<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id(); // Corresponde a pagamentoid
            $table->foreignId('cobranca_id')->constrained('cobrancas');
            $table->foreignId('cliente_id')->constrained('users');
            $table->date('data_pagamento');
            $table->decimal('valor_pago', 10, 2);
            $table->string('forma_pagamento', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};