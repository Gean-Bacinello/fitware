{{--
=====================================================================
ARQUIVO: resources/views/auth/login.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/07/2025
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de login do sistema.
    Ela permite que o usuário insira seu endereço email e senha.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/login/login.css
    - Rota: login.form
    - Controller: AutheticationController.php
=====================================================================
--}}



@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
@endpush

@section('title', 'login')

@section('content')
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="container cadastro-container p-4 p-md-5"> 
        <div class="row align-items-center">
            
            <!-- Logo à direita -->
            <div class="col-md-6 order-md-2"> 
                <div class="logo-section text-center">
                    <img src="{{asset('img/icone_fitware.png')}}" alt="Logo Fitware"> 
                    <h1 class="brand-text">
                        <span class="brand-text-fit">Fit</span><span class="brand-text-ware">ware</span>
                    </h1>
                </div>
            </div>

            <!-- Formulário de Login à esquerda -->
            <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                <h2 class="form-title">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" 
                               class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" 
                               class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Lembrar -->
                    <div class="form-check mb-4 terms-checkbox"> 
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <!-- Botão + Esqueceu senha -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-fitware w-100">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
@endsection
