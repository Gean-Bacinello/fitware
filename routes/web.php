<?php

/**------------------------------------------------------------
*  Fitware – Sistema de Gestão de Academias                   *
*  Versão: 1.0                                                *
*  Data: 06/11/2025                                           *
*  Autor: Gean Corrêa Bacinello                               *
*  Equipe: Rafael Kendi; Rafael Oliveira.                     *
*  Linguagens Utilizadas: PHP /  HTML / CSS / Laravel.        *
*  Banco de Dados: Mysql                                      *
*                                                             *
*  Arquivo web.php: destina a logica das rotas do sistema     *
*  para realizar a funções do sistema.                        *
* -------------------------------------------------------------
 */

use App\Http\Controllers\ContaController;                      
use App\Http\Controllers\TreinoController;
use App\Http\Controllers\ChatIAController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InstrutorController;
use App\Http\Controllers\AutheticationController;
use App\Http\Controllers\Cliente\MeuTreinoController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



// ---------------------------------------------------------------
// Força o logout 
// ---------------------------------------------------------------
Route::get('/forcar-logout', function() {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login')->with('status', 'Você foi deslogado com sucesso!');
});




// ---------------------------------------------------------------
// ROTAS PARA USUÁRIOS AUTENTICADOS (todos os tipos)
// ---------------------------------------------------------------
Route::group(['middleware' => 'auth'], function () {
    
    // Rota de Logout (acessível por todos os tipos logados)
    Route::post('/logout', [AutheticationController::class, 'logout'])->name('logout');

    // Rota para Conta
    Route::get('/conta', [ContaController::class, 'show'])->name('conta.show');
    Route::get('/conta/editar', [ContaController::class, 'edit'])->name('conta.edit');
    Route::post('/conta/update', [ContaController::class, 'update'])->name('conta.update');
    Route::post('/conta/deletar', [ContaController::class, 'destroy'])->name('conta.delete');


    // ---------------------------------------------------------------
    // GRUPO: SOMENTE GESTOR
    // ---------------------------------------------------------------
    Route::group(['middleware' => 'tipo:gestor'], function () {
        
        #----------- Rota Para o Dashboard --------------
        Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');

        #----------- Rotas Para CRUD Instrutor --------------
        Route::get('/instrutores', [InstrutorController::class, 'index'])->name('instrutores.index');
        Route::get('/instrutores/create', [InstrutorController::class, 'create'])->name('instrutores.create');
        Route::post('/instrutores', [InstrutorController::class, 'store'])->name('instrutores.store');
        Route::get('/instrutores/{id}', [InstrutorController::class, 'show'])->name('instrutores.show');
        Route::get('/instrutores/{id}/edit', [InstrutorController::class, 'edit'])->name('instrutores.edit');
        Route::put('/instrutores/{id}', [InstrutorController::class, 'update'])->name('instrutores.update');
        Route::delete('/instrutores/{id}', [InstrutorController::class, 'destroy'])->name('instrutores.destroy');
    });

    // ---------------------------------------------------------------
    // GRUPO: GESTOR E INSTRUTOR
    // ---------------------------------------------------------------
    Route::group(['middleware' => 'tipo:gestor,instrutor'], function () {

        #----------- Rotas Para CRUD Cliente --------------
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

        #----------- Rotas Para Gestão de Treinos --------------
        Route::get('/treinos/clientes', [TreinoController::class, 'listarClientes'])->name('treinos.listarClientes');
        Route::get('/treinos/atribuir/{cliente}', [TreinoController::class, 'create'])->name('treinos.create');
        Route::post('/treinos', [TreinoController::class, 'store'])->name('treinos.store');
        Route::get('/treinos/{ficha}', [TreinoController::class, 'show'])->name('treinos.show');
        Route::get('/treinos/{ficha}/edit', [TreinoController::class, 'edit'])->name('treinos.edit');
        Route::put('/treinos/{ficha}', [TreinoController::class, 'update'])->name('treinos.update');
        Route::get('/treinos/historico/{cliente}', [TreinoController::class, 'historico'])->name('treinos.historico');

        #----------- Rotas Para CRUD Exercicios  --------------
        Route::get('/exercicios', [ExercicioController::class, 'index'])->name('exercicios.index');
        Route::get('/exercicios/create', [ExercicioController::class, 'create'])->name('exercicios.create');
        Route::post('/exercicios', [ExercicioController::class, 'store'])->name('exercicios.store');
        Route::get('/exercicios/{exercicio}/edit', [ExercicioController::class, 'edit'])->name('exercicios.edit');
        Route::put('/exercicios/{exercicio}', [ExercicioController::class, 'update'])->name('exercicios.update');
        Route::delete('/exercicios/{exercicio}', [ExercicioController::class, 'destroy'])->name('exercicios.destroy');

    });

        Route::group(['middleware' => 'tipo:gestor,instrutor,cliente'], function () {
    
        #----------- Rotas Para Chat IA -------------------
        Route::post('/chat-ia', [ChatIAController::class, 'responder'])->name('chat-ia.responder');
        Route::get('/chat-ia', function () {
            return view('chat');
        })->name('chat-ia.view');
        
    });

    // ---------------------------------------------------------------
    // GRUPO: SOMENTE CLIENTE (Ex: ver próprio treino)
    // ---------------------------------------------------------------
        Route::group(['middleware' => 'tipo:cliente'], function () {
        
        Route::get('/meu-treino', [MeuTreinoController::class, 'index'])
             ->name('cliente.meu-treino');

    });

});


// ---------------------------------------------------------------
// ROTAS PARA VISITANTES (GUEST)
// ---------------------------------------------------------------
Route::group(['middleware' => 'guest'], function () {
    
    #----------- Rota Para Pagina Inicial -------------------
    Route::get('/', function () {
        return view('home');
    });

    #----------- Rotas Para Autenticação -------------------
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store'])->name('register');

    Route::get('/login', [AutheticationController::class, 'login'])->name('login.form');
    Route::post('/login', [AutheticationController::class, 'logar'])->name('login');

    Route::get('/forget-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forget-password', [PasswordResetController::class, 'email'])->name('password.email');

    Route::get('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

