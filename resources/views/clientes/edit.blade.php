{{--
=====================================================================
ARQUIVO: resources/views/clientes/edit.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o formulário de edição de um cliente no sistema.
    atravez do ID.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/clientes/clientes-edit.css
    - Rota: clientes.edit
    - Controller: ClienteController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/clientes/clientes-edit.css') }}">
@endpush


@section('title', 'Editar Cliente')

@section('content')
    <div class="container mt-2 mb-5">

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h2 class="mb-0">Editar Informações do Cliente</h2>
                <p class="text-muted mb-0">Atualize os dados do cliente nos campos abaixo.</p>
            </div>

            {{-- O formulário agora engloba o corpo e o rodapé do card --}}
            <form id="edit-form" action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">

                        <!-- Coluna 1: Dados Pessoais -->
                        <div class="col-lg-4 border-end-lg">
                            <h4 class="mb-3">Dados Pessoais</h4>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name"
                                    @error('name') is-invalid @enderror value="{{ old('name', $cliente->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email<span style="color: red">*</span></label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email"
                                    @error('email') is-invalid @enderror value="{{ old('email', $cliente->email) }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control form-control-sm" id="telefone" name="telefone"
                                    @error('telefone') is-invalid @enderror
                                    value="{{ old('telefone', $cliente->clienteInformacoes->telefone ?? '') }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date"
                                    class="form-control form-control-sm @error('data_nascimento') is-invalid @enderror"
                                    id="data_nascimento" name="data_nascimento"
                                    value="{{ old('data_nascimento', $cliente->clienteInformacoes->data_nascimento ?? '') }}">
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select form-select-sm @error('sexo') is-invalid @enderror"
                                    id="sexo" name="sexo">
                                    <option value="">Selecione...</option>
                                    <option value="masculino"
                                        {{ old('sexo', $cliente->clienteInformacoes->sexo ?? '') == 'masculino' ? 'selected' : '' }}>
                                        Masculino
                                    </option>
                                    <option value="feminino"
                                        {{ old('sexo', $cliente->clienteInformacoes->sexo ?? '') == 'feminino' ? 'selected' : '' }}>
                                        Feminino
                                    </option>
                                    <option value="outro"
                                        {{ old('sexo', $cliente->clienteInformacoes->sexo ?? '') == 'outro' ? 'selected' : '' }}>
                                        Outro
                                    </option>
                                </select>

                                @error('sexo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 2: Dados de Saúde -->
                        <div class="col-lg-4 border-end-lg">
                            <h4 class="mb-3">Dados de Saúde</h4>
                            <div class="mb-3">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" step="0.1"
                                    class="form-control form-control-sm  @error('peso') is-invalid @enderror" id="peso"
                                    name="peso" value="{{ old('peso', $cliente->clienteInformacoes->peso ?? '') }}">
                                @error('peso')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="altura" class="form-label">Altura (m)</label>
                                <input type="number" step="0.01"
                                    class="form-control form-control-sm @error('altura') is-invalid @enderror"
                                    id="altura" name="altura"
                                    value="{{ old('altura', $cliente->clienteInformacoes->altura ?? '') }}">
                                @error('altura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="condicoes_medicas" class="form-label">Condições Médicas</label>
                                <textarea class="form-control form-control-sm @error('condicoes_medicas') is-invalid @enderror" id="condicoes_medicas"
                                    name="condicoes_medicas" rows="8">{{ old('condicoes_medicas', $cliente->clienteInformacoes->condicoes_medicas ?? '') }}</textarea>
                                @error('condicoes_medicas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Coluna 3: Configurações -->
                        <div class="col-lg-4 border-end-lg">
                            <div class="mb-3">
                                <h4 class="mb-3">Status</h4>
                                <label for="status" class="form-label">Status da Conta</label>

                                <select class="form-select form-select-sm @error('status') is-invalid @enderror"
                                    id="status" name="status">
                                    <option value="ativo"
                                        {{ old('status', $cliente->status) == 'ativo' ? 'selected' : '' }}>
                                        Ativo
                                    </option>
                                    <option value="inativo"
                                        {{ old('status', $cliente->status) == 'inativo' ? 'selected' : '' }}>
                                        Inativo
                                    </option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">a
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Rodapé do card com os botões de ação --}}
                <div class="card-footer bg-light text-end py-3">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary me-2">Cancelar</a>

                    {{-- O atributo 'form' liga este botão ao formulário com id="edit-form" --}}
                    <button type="submit" form="edit-form" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
@endsection
