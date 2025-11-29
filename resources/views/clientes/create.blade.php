{{--
=====================================================================
ARQUIVO: resources/views/clientes/crete.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário cadastro de clientes no sistema.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/clientes/clientes-create.css
    - Rota: clientes.create
    - Controller: ClienteController.php
=====================================================================
--}}


@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/clientes/clientes-create.css') }}">
@endpush

@section('title', 'Cadastrar Cliente')

@section('content')
    <div class="container mt-4 mb-5">

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="mb-0">Cadastro de Novo Cliente</h2>
                <p class="text-muted mb-0">Insira os dados do cliente nos campos abaixo.</p>
            </div>

            {{-- Formulário aponta para a rota 'store' para criar um novo registro --}}
            <form id="create-form" action="{{ route('clientes.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="row">

                        <!-- Coluna 1: Dados Pessoais -->
                        <div class="col-lg-4 border-end-lg">
                            <h4 class="mb-3">Dados Pessoais</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name"
                                    @error('name') is-invalid @enderror value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email"  @error('email') is-invalid @enderror
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control form-control-sm" id="telefone" name="telefone"  @error('telefone') is-invalid @enderror
                                    value="{{ old('telefone') }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control form-control-sm" id="data_nascimento"  @error('data_nascimento') is-invalid @enderror
                                    name="data_nascimento" value="{{ old('data_nascimento') }}">
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select form-select-sm" id="sexo" name="sexo"  @error('sexo') is-invalid @enderror>
                                    <option value="">Selecione...</option>
                                    <option value="masculino" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>
                                        Masculino</option>
                                    <option value="feminino" {{ old('sexo') == 'feminino' ? 'selected' : '' }}>
                                        Feminino</option>
                                    <option value="outro" {{ old('sexo') == 'outro' ? 'selected' : '' }}>
                                        Outro</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 2: Dados de Saúde -->
                        <div class="col-lg-4 border-end-lg">
                            <h4 class="mb-3">Dados de Saúde</h4>
                            <div class="mb-3">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" min="0" step="0.1" class="form-control form-control-sm" id="peso"  @error('peso') is-invalid @enderror
                                    name="peso" value="{{ old('peso') }}">
                                @error('peso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="altura" class="form-label">Altura (m)</label>
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm" id="altura"  @error('altura') is-invalid @enderror
                                    name="altura" value="{{ old('altura') }}">
                                @error('altura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="condicoes_medicas" class="form-label">Condições Médicas</label>
                                <textarea class="form-control form-control-sm" id="condicoes_medicas" name="condicoes_medicas" rows="8"  @error('condicoes_medicas') is-invalid @enderror>{{ old('condicoes_medicas') }}</textarea>
                                @error('condicoes_medicas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 3: Acesso e Configurações -->
                        <div class="col-lg-4">
                            <h4 class="mb-3">Acesso ao Sistema</h4>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha <span style="color: red">*</span></label>
                                <input type="password" class="form-control form-control-sm  @error('password') is-invalid @enderror" id="password"
                                    name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Senha <span
                                        style="color: red">*</span></label>
                                <input type="password" class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror" id="password_confirmation" 
                                    name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Rodapé do card com os botões de ação --}}
                <div class="card-footer bg-light text-end py-3">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    {{-- O atributo 'form' liga este botão ao formulário --}}
                    <button type="submit" form="create-form" class="btn btn-primary">Cadastrar Cliente</button>
                </div>
            </form>
        </div>
    </div>
@endsection
