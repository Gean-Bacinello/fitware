{{--
=====================================================================
ARQUIVO: resources/views/auth/passwords/email.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/07/2025
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de envio redefinição de senha do sistema Fitware.
    Ela permite que o usuário insira seu endereço de e-mail para receber
    um link de redefinição de senha.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/auth/email.css
    - Rota: password.email
    - Controller: PasswordResetController.php
=====================================================================
--}}


@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/email.css') }}">
@endpush

@section('title', 'Redefinir Senha')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="container email-container p-4 p-md-5">
        <div class="row align-items-center">

            <!-- Logo à direita -->
            <div class="col-md-6 order-md-2">
                <div class="logo-section text-center">
                    <img src="{{asset('img/logo.png')}}" alt="Logo Fitware" class="project-logo mb-1">
                    <h1 class="brand-text">
                        <span class="brand-text-fit">Fit</span><span class="brand-text-ware">ware</span>
                    </h1>
                </div>
            </div>

            <!-- Formulário de Reset de Senha à esquerda -->
            <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                <h2 class="form-title">{{ __('Reset Password') }}</h2>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email"
                               class="form-control form-control-custom @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}"
                               required autocomplete="email" autofocus>

                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>

                    <!-- Botão -->
                    <div class="d-flex flex-column gap-2 mt-4">
                        <button type="submit" class="btn btn-fitware w-100">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

