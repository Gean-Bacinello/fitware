{{--
=====================================================================
ARQUIVO: resources/views/conta/show.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe as informaçoes de um usuario logado.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/conta/show-blade.css
    - Rota: conta.show
    - Controller: ContaController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/conta/show-blade.css') }}">
@endpush

@section('content')
    <div class="main-card mt-5 container">
        <h2 class="header-title mb-4">Minha Conta</h2>

        {{-- Seção Principal: Dados e Imagem --}}
        <div class="row profile-info-grid mb-3">
            {{-- Coluna de Detalhes --}}
            <div class="col-md-8 info-group">
                <div><strong>Nome:</strong> {{ $usuario->name }}</div>
                <div><strong>Email:</strong> {{ $usuario->email }}</div>
                <div><strong>Tipo de Usuário:</strong> {{ ucfirst($usuario->tipo_usuario) }}</div>
                <div><strong>Status:</strong> <span class="badge {{ $usuario->status == 'ativo' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($usuario->status) }}</span></div>
            </div>

            {{-- Coluna da Imagem --}}
            <div class="col-md-4 text-md-end">
                @if ($usuario->imagem_perfil_url)
                    <img src="{{ asset('storage/' . $usuario->imagem_perfil_url) }}" alt="Imagem de Perfil"
                        class="profile-image">
                @else
                    {{-- Opcional: Mostrar uma imagem padrão --}}
                    {{-- <img src="{{ asset('images/avatar_padrao.png') }}" alt="Imagem de Perfil" class="profile-image"> --}}
                @endif
            </div>
        </div>

        <hr>

        {{-- Seção de Informações Adicionais --}}
        <h5 class="info-title mt-4">Informações Adicionais</h5>

        @if ($usuario->tipo_usuario === 'cliente' && $usuario->clienteInformacoes)
            <div class="info-group mt-3">
                <div><strong>Telefone:</strong> {{ $usuario->clienteInformacoes->telefone ?? '-' }}</div>
                <div><strong>Data de Nascimento:</strong> {{ $usuario->clienteInformacoes->data_nascimento ? \Carbon\Carbon::parse($usuario->clienteInformacoes->data_nascimento)->format('d/m/Y') : '-' }}</div>
                <div><strong>Sexo:</strong> {{ ucfirst($usuario->clienteInformacoes->sexo ?? '-') }}</div>
                <div><strong>Peso:</strong> {{ $usuario->clienteInformacoes->peso ? $usuario->clienteInformacoes->peso . ' kg' : '-' }}</div>
                <div><strong>Altura:</strong> {{ $usuario->clienteInformacoes->altura ? $usuario->clienteInformacoes->altura . ' cm' : '-' }}</div>
                <div class="mt-2"><strong>Condições Médicas:</strong>
                    <p class="text-muted" style="font-size: 0.9em; margin-left: 5px;">
                        {{ $usuario->clienteInformacoes->condicoes_medicas ?? 'Nenhuma condição registrada.' }}
                    </p>
                </div>
            </div>
        @elseif ($usuario->tipo_usuario === 'instrutor' && $usuario->instrutorInformacoes)
            <div class="info-group mt-3">
                <div><strong>CREF:</strong> {{ $usuario->instrutorInformacoes->CREF ?? '-' }}</div>
                <div><strong>Telefone:</strong> {{ $usuario->instrutorInformacoes->telefone ?? '-' }}</div>
            </div>
        @else
             <div class="info-group mt-3">
                <p class="text-muted">Nenhuma informação adicional cadastrada.</p>
             </div>
        @endif


        {{-- Seção de Botões de Ação --}}
        <hr class="my-4">
        <div class="action-buttons-footer">
            
            {{-- Botão Editar --}}
            <a href="{{ route('conta.edit') }}" class="btn btn-custom-edit btn-table">
                <i class="bi bi-pencil-square"></i> Editar Conta
            </a>

            {{-- Botão Excluir Conta --}}
           {{-- <form action="{{ route('conta.delete') }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-custom-delete btn-table"
                    onclick="return confirm('Tem certeza que deseja EXCLUIR sua conta? Esta ação é irreversível.');">
                    <i class="bi bi-trash"></i> Excluir Conta
                </button>
            </form> --}}

            {{-- Botão Sair (Logout) --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-custom-logout btn-table"
                    onclick="event.preventDefault(); 
                    if(confirm('Você tem certeza que deseja sair?')) {
                        document.getElementById('logout-form').submit();
                    }">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </form>

        </div>

    </div>
@endsection