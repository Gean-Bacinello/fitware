{{--
=====================================================================
ARQUIVO: resources/views/auth/register.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/07/2025
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de registro no sistema.
    Ela permite que o usuário informe seu endereço email e senha.
    para o seu cadastro  -> rever o tipo de usuario
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/register/register.css
    - Rota: register
    - Controller: RegisterController.php
=====================================================================
--}}


@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register/register.css') }}">
@endpush

@section('title', 'Cadastre-se')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="container register-container p-4 p-md-5">
        <div class="row align-items-center">

            <!-- Logo à direita -->
            <div class="col-md-6 order-md-2">
                <div class="logo-section text-center">
                    <img src="{{asset('img/logo.png')}}" alt="Logo Fitware" class="img-fluid">
                    <h1 class="brand-text">
                        <span class="brand-text-fit">Fit</span><span class="brand-text-ware">ware</span>
                    </h1>
                </div>
            </div>

            <!-- Formulário de Cadastro à esquerda -->
            <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                <h2 class="form-title">{{ __('Register') }}</h2>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control form-control-custom @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control form-control-custom" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Botão -->
                    <div class="d-flex flex-column gap-2 mt-4">
                        <button type="submit" class="btn btn-fitware w-100">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection

