{{--
=====================================================================
ARQUIVO: resources/views/home.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 09/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
   view inicial ao acessar o sistema.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: public/css/home/home-style.css
    - Rota: home
    - Controller: -
=====================================================================
--}}

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('img/favicon_io/favicon.ico') }}" type="image/x-icon">

    <title>Bem-vindo(a) ao FitWare</title>

     {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

    {{-- CSS  --}}
    <link rel="stylesheet" href="{{ asset('css/home/home-style.css') }}">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-lg-7 col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center text-white" id="branding-side">
               <div class="branding-content text-center">
                   
                    <img src="{{ asset('img/logo.png') }}" alt="Logo do Projeto" class="project-logo mb-4">
                    
                    <h1 class="display-4">Bem-vindo(a) ao FitWare</h1>
                    <p class="lead">Sua jornada para uma vida mais saudável começa aqui.</p>
               </div>
            </div>

            <div class="col-lg-5 col-md-6 d-flex flex-column justify-content-center align-items-center bg-light">
                <div class="action-container text-center">
                    <h2 class="mb-3">Comece Agora</h2>
                    <p class="lead text-muted mb-4">Acesse sua conta ou crie um novo cadastro para ter acesso completo à plataforma.</p>
                    
                    <div class="d-grid gap-3">
                        {{-- Botão que leva para a página de LOGIN  --}}
                        <a href="{{ route('login') }}" class="btn btn-primary-custom btn-lg">Entrar na minha conta</a>
                        
                        {{-- Botão que leva para a página de REGISTRO  --}}
                        <a href="{{ route('register') }}" class="btn btn-secondary-custom btn-lg">Quero me cadastrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/menu-dashboard.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js') }}"></script>

</body>
</html>