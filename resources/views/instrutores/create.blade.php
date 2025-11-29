{{--
=====================================================================
ARQUIVO: resources/views/instrutores/crete.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário cadastro de instrutorers no sistema.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/instrutores/create-instrutores.css
    - Rota: instrutores.create
    - Controller: InstrutorController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/instrutores/create-instrutores.css') }}">
@endpush

@section('title', 'Cadastrar Instrutor')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="mb-0">Cadastro de Novo Instrutor</h2>
                <p class="text-muted mb-0">Insira os dados do Instrutor nos campos abaixo.</p>
            </div>

            <form id="create-form" action="{{ route('instrutores.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 border-end-lg">
                            <h4 class="mb-3">Dados Pessoais</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo <span
                                        style="color: red">*</span></label>
                                <input type="text"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                <input type="email"
                                    class="form-control form-control-sm @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text"
                                    class="form-control form-control-sm @error('telefone') is-invalid @enderror"
                                    id="telefone" name="telefone" value="{{ old('telefone') }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="CREF" class="form-label">CREF</label>
                                <input type="number" min="0"
                                    class="form-control form-control-sm @error('CREF') is-invalid @enderror" id="CREF"
                                    name="CREF" value="{{ old('CREF') }}">
                                @error('CREF')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h4 class="mb-3">Acesso ao Sistema</h4>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha <span style="color: red">*</span></label>
                                <input type="password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Senha <span
                                        style="color: red">*</span></label>

                                <input type="password"
                                    class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation" required>

                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-end py-3">
                    <a href="{{ route('instrutores.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" form="create-form" class="btn btn-primary">Cadastrar Instrutor</button>
                </div>
            </form>
        </div>
    </div>
@endsection
