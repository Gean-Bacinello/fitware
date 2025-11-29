{{--
=====================================================================
ARQUIVO: resources/views/conta/edit.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe as informaçoes de um usuario logado para a edição.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/conta/edit-blade.css
    - Rota: conta.edit
    - Controller: ContaController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/conta/edit-blade.css') }}">
@endpush

@section('content')
    <div class="main-card mt-2 container">
        <h2 class="header-title mb-4">Editar Minha Conta</h2>

        {{-- Exibe Alerta de Erros --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Opa!</strong> Verifique os erros abaixo.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('conta.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- DADOS PRINCIPAIS --}}
            <div class="row">
                {{-- Coluna 1: Nome e Email --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}"
                            class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Coluna 2: Senha e Confirmação --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="password">Senha (Deixe vazio para manter atual)</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div> {{-- Fim da .row de Dados Principais --}}


            {{-- CAMPO DE IMAGEM (Abaixo das colunas) --}}
            <div class="mb-3 mt-3">
                <label class="form-label" for="imagem_perfil_url">Imagem de Perfil (Opcional)</label>
                <input type="file" name="imagem_perfil_url" id="imagem_perfil_url" class="form-control @error('imagem_perfil_url') is-invalid @enderror"
                    accept="image/*">
                <div class="form-text">Deixe em branco para manter a imagem atual.</div>
                @error('imagem_perfil_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($usuario->imagem_perfil_url)
                    <div class="mt-2">
                        <label class="form-label d-block">Imagem Atual:</label>
                        <img src="{{ asset('storage/' . $usuario->imagem_perfil_url) }}" alt="Imagem atual"
                            class="img-thumbnail" width="100">
                    </div>
                @endif
            </div>

            {{-- Linha divisória para separar dados de login e dados de perfil --}}
            <hr class="my-4">

            {{-- Campos extras para clientes --}}
            @if ($usuario->tipo_usuario === 'cliente')
                <h5 class="mt-4">Informações do Cliente</h5>

                <div class="mb-3">
                    <label class="form-label" for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone"
                        value="{{ old('telefone', $usuario->clienteInformacoes->telefone ?? '') }}" class="form-control @error('telefone') is-invalid @enderror">
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="data_nascimento">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento"
                        value="{{ old('data_nascimento', $usuario->clienteInformacoes->data_nascimento ?? '') }}"
                        class="form-control @error('data_nascimento') is-invalid @enderror">
                    @error('data_nascimento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" class="form-select @error('sexo') is-invalid @enderror">
                        <option value="" @if (old('sexo', $usuario->clienteInformacoes->sexo ?? '') == '') selected @endif>Selecione</option>
                        <option value="masculino" @if (old('sexo', $usuario->clienteInformacoes->sexo ?? '') == 'masculino') selected @endif>Masculino</option>
                        <option value="feminino" @if (old('sexo', $usuario->clienteInformacoes->sexo ?? '') == 'feminino') selected @endif>Feminino</option>
                        <option value="outro" @if (old('sexo', $usuario->clienteInformacoes->sexo ?? '') == 'outro') selected @endif>Outro</option>
                    </select>
                    @error('sexo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="peso">Peso (kg)</label>
                    <input type="number" step="0.01" name="peso" id="peso"
                        value="{{ old('peso', $usuario->clienteInformacoes->peso ?? '') }}" class="form-control @error('peso') is-invalid @enderror">
                    @error('peso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="altura">Altura (cm)</label>
                    <input type="number" step="0.01" name="altura" id="altura"
                        value="{{ old('altura', $usuario->clienteInformacoes->altura ?? '') }}" class="form-control @error('altura') is-invalid @enderror">
                    @error('altura')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="condicoes_medicas">Condições Médicas</label>
                    <textarea name="condicoes_medicas" id="condicoes_medicas" class="form-control @error('condicoes_medicas') is-invalid @enderror">{{ old('condicoes_medicas', $usuario->clienteInformacoes->condicoes_medicas ?? '') }}</textarea>
                    @error('condicoes_medicas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- Campos extras para instrutores --}}
            @if ($usuario->tipo_usuario === 'instrutor')
                <h5 class="mt-4">Informações do Instrutor</h5>

                <div class="mb-3">
                    <label class="form-label" for="CREF">CREF</label>
                    <input type="text" name="CREF" id="CREF"
                        value="{{ old('CREF', $usuario->instrutorInformacoes->CREF ?? '') }}" class="form-control @error('CREF') is-invalid @enderror">
                    @error('CREF')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone"
                        value="{{ old('telefone', $usuario->instrutorInformacoes->telefone ?? '') }}"
                        class="form-control @error('telefone') is-invalid @enderror">
                    @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- BOTÕES DE AÇÃO --}}
            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('conta.show') }}" class="btn btn-custom-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-custom-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
@endsection