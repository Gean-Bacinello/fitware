{{--
=====================================================================
ARQUIVO: resources/views/clientes/show.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe as informaçoes de um cliente.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/clientes/clientes-show.css
    - Rota: clientes.show
    - Controller: ClienteController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@section('title', 'Informação do Cliente')

@section('content')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/clientes/clientes-show.css') }}">
    @endpush

    </head>

    <body>
        <div class="container mt-5 mb-5">
            <div class="card shadow-sm">
                {{-- CABEÇALHO --}}
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Detalhes do Cliente</h2>
                        {{-- Exibe o nome do cliente dinamicamente --}}
                        <p class="text-muted mb-0">Visualizando os dados de: <strong>{{ $cliente->name }}</strong></p>
                    </div>
                    <div>
                        {{-- Botão para voltar para a lista de clientes --}}
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Voltar para a Lista
                        </a>
                    </div>
                </div>

                {{-- CORPO COM OS DADOS --}}
                <div class="card-body">
                    <div class="row">
                        {{-- COLUNA ESQUERDA: DADOS DE ACESSO --}}
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <h4 class="mb-3 border-bottom pb-2">Dados de Acesso</h4>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Nome Completo:</strong>
                                <span>{{ $cliente->name }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Email:</strong>
                                <span>{{ $cliente->email }}</span>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted">Status:</strong>
                                {{-- Exibe o status com badge de cor --}}
                                @if ($cliente->status == 'ativo')
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </div>
                        </div>

                        {{-- COLUNA DIREITA: INFORMAÇÕES PESSOAIS --}}
                        <div class="col-lg-6">
                            <h4 class="mb-3 border-bottom pb-2">Informações Pessoais</h4>

                            @if ($cliente->clienteInformacoes)
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Data de Nascimento:</strong>
                                        <span>{{ $cliente->clienteInformacoes->data_nascimento }}</span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Telefone:</strong>
                                        <span>{{ $cliente->clienteInformacoes->telefone }}</span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Sexo:</strong>
                                        <span>{{ $cliente->clienteInformacoes->sexo }}</span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Peso:</strong>
                                        <span>{{ $cliente->clienteInformacoes->peso }} kg</span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Altura:</strong>
                                        <span>{{ $cliente->clienteInformacoes->altura }} m</span>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <strong class="d-block text-muted">Condições Médicas:</strong>
                                        <span>{{ $cliente->clienteInformacoes->condicoes_medicas }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Nenhuma informação pessoal cadastrada para o {{ $cliente->name }}.</p>
                            @endif

                            <div class="mt-3">
                                <strong class="d-block text-muted">Data de Cadastro:</strong>
                                <span>{{ $cliente->created_at->format('d/m/Y \à\s H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
