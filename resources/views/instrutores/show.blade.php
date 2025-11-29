{{--
=====================================================================
ARQUIVO: resources/views/instrutores/show.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe as informaçoes de um instrutor especifico.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/instrutores/show-instrutores.css
    - Rota: instrutores.show
    - Controller: InstrutoresController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@section('title', 'Informação do Instrutor')

@section('content')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/instrutores/show-instrutores.css') }}">
    @endpush

    </head>

    <body>
        <div class="container mt-5 mb-5">
            <div class="card shadow-sm">

                {{-- CABEÇALHO --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Detalhes do Instrutor</h2>

                        {{-- Exibe o nome do instrutor dinamicamente --}}
                        <p class="text-muted mb-0">Visualizando os dados de: <strong>{{ $instrutor->name }}</strong></p>
                    </div>
                    <div>

                        {{-- Botão para voltar para a lista de instrutores --}}
                        <a href="{{ route('instrutores.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Voltar para a Lista
                        </a>
                    </div>
                </div>

                {{-- CORPO COM OS DADOS --}}
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <h4 class="mb-3 border-bottom pb-2">Dados Pessoais</h4>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Nome Completo:</strong>
                                <span>{{ $instrutor->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Email:</strong>
                                <span>{{ $instrutor->email }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Telefone:</strong>

                                {{-- Verifica se o telefone existe antes de exibir --}}
                                <span>{{ $instrutor->instrutorinformacoes->telefone ?? 'Não informado' }}</span>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h4 class="mb-3 border-bottom pb-2">Configurações da Conta</h4>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Status da Conta:</strong>
                                @if ($instrutor->status == 'ativo')
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">CREF:</strong>
                                
                                {{-- Verifica se o telefone existe antes de exibir --}}
                                <span>{{ $instrutor->InstrutorInformacoes->CREF ?? 'Não informado' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Data de Cadastro:</strong>
                            
                                <span>{{ $instrutor->created_at->format('d/m/Y \à\s H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    
    @endsection
