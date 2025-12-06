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
        Schema::table('exercicios', function (Blueprint $table) {
            // Remove a coluna 'divisao'. 
            // Os dados que estavam nela serão apagados permanentemente.
            $table->dropColumn('divisao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercicios', function (Blueprint $table) {
            // Se precisar desfazer, recria a coluna (mas os dados voltam vazios)
            $table->string('divisao')->nullable();
        });
    }
};