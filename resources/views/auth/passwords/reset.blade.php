{{--
=====================================================================
ARQUIVO: resources/views/auth/passwords/reset.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/07/2025
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de envio redefinição de senha do sistema Fitware.
    Ela permite que o usuário insira seu endereço e nova senha.

    Principais funções:
      - Envio de link para redefinir a senha do usuário (válido por 60 minutos).
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/auth/reset.css
    - Rota:  passwords.update
    - Controller: PasswordResetController.php
=====================================================================

--}}


@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth/reset.css') }}">
@endpush

@section('title', 'Redefinir Senha')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
    <div class="container reset-container p-4 p-md-5">
        <div class="row align-items-center">

            <!-- Logo à direita -->
            <div class="col-md-6 order-md-2">
                <div class="logo-section text-center">
                    <img src="{{asset('img/logo.png')}}" alt="Logo Fitware" class="project-logo">
                    <h1 class="brand-text">
                        <span class="brand-text-fit">Fit</span><span class="brand-text-ware">ware</span>
                    </h1>
                </div>
            </div>

            <!-- Formulário de Redefinição de Senha à esquerda -->
            <div class="col-md-6 order-md-1 mb-4 mb-md-0">
                <h2 class="form-title">{{ __('Reset Password') }}</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    {{-- Este campo oculto é crucial para a segurança do reset PASSA O TOKEN --}}
                    <input type="hidden" name="token" value="{{ request()->token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control form-control-custom @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control form-control-custom @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control form-control-custom" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Botão -->
                    <div class="d-flex flex-column gap-2 mt-4">
                        <button type="submit" class="btn btn-fitware w-100">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
@endsection

