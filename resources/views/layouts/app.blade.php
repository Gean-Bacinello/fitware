{{--
=====================================================================
ARQUIVO: resources/views/layouts/app.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    layouts base para as telas de login e recuperação senha
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

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    <link rel="shortcut icon" href="{{ asset('img/favicon_io/favicon.ico') }}" type="image/x-icon">

    @stack('styles')

    <title>@yield('title')</title>

</head>
<body>
           
        <main class="container">
            @yield('content')
        </main>
  
    <script src="{{ asset('js/menu-dashboard.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>
</body>
</html>
