{{--
=====================================================================
ARQUIVO: resources/views/layouts/dashboard.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    layouts base para as telas principais do sistema
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: --
    - Rota: --
    - Controller: --
=====================================================================
--}}

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    {{-- CSS Personalizado do menu --}}
    <link rel="stylesheet" href="{{ asset('css/menu-dashboard.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon_io/favicon.ico') }}" type="image/x-icon">

    @stack('scripts')

    @stack('styles')

    <title>@yield('title')</title>
</head>

<body>

    {{-- MENU LATEARAL --}}
    <nav class="menu_lateral">
        <div class="btn_expandir">
            <div class="logo_container">
                <img src="{{ asset('img/icone_fitware.png') }}" alt="Logo Fitware" class="logo-img" id="btn-exp">
                <span class="logo-text">Fitware</span>
            </div>

        </div>

        <ul>
            {{-- Garante que só usuários logados vejam os links --}}
            @auth

                {{-- 1. LINKS EXCLUSIVOS DO GESTOR --}}
                @if (Auth::user()->tipo_usuario == 'gestor')
                    <li class="item_menu {{ Route::is('dashboard.*') ? 'ativo' : '' }}">
                        <a href="{{ route('dashboard.index') }}">
                            <span class="icon"><i class="bi bi-columns"></i></span>
                            <span class="txt-link">Dashboard</span>
                        </a>
                    </li>
                    <li class="item_menu {{ Route::is('instrutores.*') ? 'ativo' : '' }}">
                        <a href="{{ route('instrutores.index') }}">
                            <span class="icon"><i class="bi bi-person-fill"></i></span>
                            <span class="txt-link">Instrutores</span>
                        </a>
                    </li>
                @endif


                {{-- 2. LINKS PARA GESTOR E INSTRUTOR --}}
                @if (in_array(Auth::user()->tipo_usuario, ['gestor', 'instrutor']))
                    <li class="item_menu {{ Route::is('treinos.*') ? 'ativo' : '' }}">
                        <a href="{{ route('treinos.listarClientes') }}">
                            <span class="icon"><i class="bi bi-person-lines-fill"></i></span>
                            <span class="txt-link">Treinos</span>
                        </a>
                    </li>
                    <li class="item_menu {{ Route::is('exercicios.*') ? 'ativo' : '' }}">
                        <a href="{{ route('exercicios.index') }}">
                            <span class="icon"><i class="bi bi-list-task"></i></span>
                            <span class="txt-link">Exercicios</span>
                        </a>
                    </li>
                    <li class="item_menu {{ Route::is('clientes.*') ? 'ativo' : '' }}">
                        <a href="{{ route('clientes.index') }}">
                            <span class="icon"><i class="bi bi-people-fill"></i></span>
                            <span class="txt-link">Clientes</span>
                        </a>
                    </li>
                @endif

                {{-- 3. LINKS PARA GESTOR, INSTRUTOR E CLIENTE --}}
                @if (in_array(Auth::user()->tipo_usuario, ['gestor', 'instrutor', 'cliente']))
                    <li class="item_menu {{ Route::is('chat-ia.*') ? 'ativo' : '' }}">
                        <a href="{{ route('chat-ia.view') }}">
                            <span class="icon"><i class="bi bi-diamond"></i></span>
                            <span class="txt-link">IA</span>
                        </a>
                    </li>
                @endif

                {{-- 4. LINKS PARA CLIENTE --}}
                @if (Auth::user()->tipo_usuario == 'cliente')
                    <li class="item_menu {{ Route::is('cliente.meu-treino') ? 'ativo' : '' }}">
                        <a href="{{ route('cliente.meu-treino') }}">
                            <span class="icon"><i class="bi bi-person-workspace"></i></span>
                            <span class="txt-link">Treino</span>
                        </a>
                    </li>
                @endif

                {{-- 5. LINKS PARA TODOS OS USUÁRIOS LOGADOS --}}
                <li class="item_menu {{ Route::is('conta*') ? 'ativo' : '' }}">
                    <a href="{{ route('conta.show') }}">
                        <span class="icon"><i class="bi bi-person-circle"></i></span>
                        <span class="txt-link">Conta</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); 
                        if(confirm('Você tem certeza que deseja sair?')) {
                            document.getElementById('logout-form').submit();
                        }">
                        <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span class="txt-link">Sair</span>
                    </a>
                </li>

            @endauth
        </ul>

        {{-- FORMULÁRIO DE LOGOUT --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

    </nav>

    {{-- CONTEUDO PRINCIPAL --}}
    <main>
        <div class="container">
            <div class="container mt-3"> 
            </div>
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/menu-dashboard.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>

</body>

</html>
