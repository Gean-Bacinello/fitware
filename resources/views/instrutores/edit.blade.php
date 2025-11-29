{{--
=====================================================================
ARQUIVO: resources/views/instrutores/edit.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de edição de um instrutor no sistema.
    atravez do ID.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/instrutores/edit-instrutores.css
    - Rota: instrutores.edit
    - Controller: InstrutorController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/instrutores/edit-instrutores.css') }}">
@endpush

@section('title', 'Editar Instrutor')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="mb-0">Editar Dados do Instrutor</h2>
                <p class="text-muted mb-0">Altere os dados do Instrutor nos campos abaixo.</p>
            </div>

            <form id="edit-form" action="{{ route('instrutores.update', $instrutor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 border-end-lg">
                            <h4 class="mb-3">Dados Pessoais</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo <span
                                        style="color: red">*</span></label>
                                <input type="text"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $instrutor->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                <input type="email"
                                    class="form-control form-control-sm @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $instrutor->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text"
                                    class="form-control form-control-sm @error('telefone') is-invalid @enderror"
                                    id="telefone" name="telefone"
                                    value="{{ old('telefone', $instrutor->InstrutorInformacoes->telefone ?? '') }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="CREF" class="form-label">CREF</label>
                                <input type="number"
                                    class="form-control form-control-sm @error('CREF') is-invalid @enderror" id="CREF"
                                    name="CREF" value="{{ old('CREF', $instrutor->InstrutorInformacoes->CREF ?? '') }}">
                                @error('CREF')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h4 class="mb-3">Acesso e Status</h4>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span style="color: red">*</span></label>
                                <select class="form-select form-select-sm @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                    <option value="ativo"
                                        {{ old('status', $instrutor->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inativo"
                                        {{ old('status', $instrutor->status) == 'inativo' ? 'selected' : '' }}>Inativo
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h4 class="mb-3 mt-4">Alterar Senha</h4>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <small class="form-text text-muted d-block mb-1">Deixe em branco para não alterar.</small>
                                <input type="password"
                                    class="form-control form-control-sm @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>

                                <input type="password"
                                    class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation">

                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-end py-3">
                    <a href="{{ route('instrutores.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" form="edit-form" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
@endsection
