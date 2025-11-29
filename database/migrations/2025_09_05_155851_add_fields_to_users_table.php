<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Seus campos personalizados da tabela Usuarios
            $table->enum('tipo_usuario', ['gestor', 'instrutor', 'cliente'])->after('password');
            $table->string('status', 20)->nullable()->after('tipo_usuario');
            // Campo para a imagem de perfil
            $table->string('imagem_perfil_url')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_usuario', 'status', 'imagem_perfil_url']);
        });
    }
};