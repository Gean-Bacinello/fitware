<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Atribuímos a aplicação a uma variável $app
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Seu alias (Isto está correto e fica aqui)
        $middleware->alias([
            'tipo' => \App\Http\Middleware\VerificarTipoUsuario::class,
        ]);
        
        // Removemos o $middleware->using() daqui.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create(); // <-- Note que o ->create() está aqui


// =========================================================
// vvv ADICIONE ESTE BLOCO NOVO AQUI vvv
// =========================================================

// 2. Usamos o método 'singleton' do container para
//    registrar nossa substituição.

// Isto diz: "Quando qualquer parte do Laravel pedir a classe
// padrão 'RedirectIfAuthenticated', entregue a nossa classe
// 'RedirecionaSeAutenticado' no lugar."

$app->singleton(
    // A classe padrão do Framework
    \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
    
    // A sua classe que criamos (o "jeito correto")
    \App\Http\Middleware\RedirecionaSeAutenticado::class
);

// =========================================================
// ^^^ FIM DO BLOCO NOVO ^^^
// =========================================================


// 3. Retornamos a instância do $app
return $app;