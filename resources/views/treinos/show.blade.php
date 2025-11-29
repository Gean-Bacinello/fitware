{{--
=====================================================================
ARQUIVO: resources/views/treinos/show.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 09/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
   
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/treinos/show-treinos.css
    - Rota: treinos.listarClientes
    - Controller: TreinoController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
   <link rel="stylesheet" href="{{ asset('css/treinos/show-treinos.css') }}">
@endpush

@section('title', 'Detalhes do Treino')

@section('content')
<div class="container mt-2">
    <div class="main-card">
        
        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="header-title mb-1">{{ $ficha->treino->nome_treino }}</h2>
                <p class="text-muted mb-0">
                    Cliente: <strong>{{ $ficha->cliente->name }}</strong> |
                    Atribuído em: <strong>{{ $ficha->data_atribuicao->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div>
                <a href="{{ route('treinos.listarClientes') }}" class="btn btn-custom-dark-grey">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        {{-- Mensagens de Feedback --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Coluna Principal com Informações e Exercícios --}}
            <div class="col-lg-8">
                <h4 class="section-title"><i class="bi bi-info-circle"></i> Informações Gerais</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Status da Ficha</small>
                        <p class="fw-bold mb-0">
                             <span class="badge {{ $ficha->status_ficha === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($ficha->status_ficha) }}
                            </span>
                        </p>
                    </div>
                     <div class="col-md-6 mb-3">
                        <small class="text-muted">Total de Exercícios</small>
                        <p class="fw-bold mb-0">{{ $ficha->treino->exercicios->count() }} exercício(s)</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Instrutor Responsável</small>
                        <p class="mb-0">{{ $ficha->instrutor->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Data de Criação</small>
                        <p class="mb-0">{{ $ficha->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($ficha->treino->descricao)
                        <div class="col-md-12">
                            <small class="text-muted">Descrição</small>
                            <p class="mb-0 fst-italic">"{{ $ficha->treino->descricao }}"</p>
                        </div>
                    @endif
                </div>
                <div class="mt-3">
                     <a href="{{ route('treinos.edit', $ficha->id) }}" class="btn btn-custom-edit">
                        <i class="bi bi-pencil"></i> Editar Treino
                    </a>
                </div>

                {{-- Lista de Exercícios --}}
                <h4 class="section-title"><i class="bi bi-list-check"></i> Exercícios do Treino</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-header-custom">
                            <tr>
                                <th scope="col">Exercício</th>
                                <th scope="col">Séries</th>
                                <th scope="col">Repetições</th>
                                <th scope="col">Carga</th>
                                <th scope="col">Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ficha->treino->exercicios as $exercicio)
                                <tr>
                                    <td>
                                        <strong>{{ $exercicio->nome_exercicio }}</strong>
                                        @if($exercicio->grupo_muscular)
                                            <br>
                                            <small class="text-muted">{{ $exercicio->grupo_muscular }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $exercicio->pivot->series ?? '-' }}</td>
                                    <td>{{ $exercicio->pivot->repeticoes ?? '-' }}</td>
                                    <td>{{ $exercicio->pivot->carga ?? '-' }}</td>
                                    <td><small>{{ $exercicio->pivot->observacoes ?? '-' }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Nenhum exercício cadastrado neste treino.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Coluna Lateral com Histórico --}}
            <div class="col-lg-4">
                 <h4 class="section-title"><i class="bi bi-clock-history"></i> Histórico de Treinos</h4>
                 <div class="list-group">
                    @php
                        $todasFichas = $ficha->cliente->fichasComoCliente()
                            ->with('treino')
                            ->orderBy('data_atribuicao', 'desc')
                            ->get();
                    @endphp

                    @forelse($todasFichas as $fichaHistorico)
                        <a href="{{ route('treinos.show', $fichaHistorico->id) }}"
                           class="list-group-item list-group-item-action {{ $fichaHistorico->id === $ficha->id ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <p class="mb-1 fw-bold">{{ Str::limit($fichaHistorico->treino->nome_treino, 25) }}</p>
                                @if($fichaHistorico->id === $ficha->id)
                                    <small><i class="bi bi-eye-fill"></i></small>
                                @endif
                            </div>
                            <small>Atribuído em: {{ $fichaHistorico->data_atribuicao->format('d/m/Y') }}</small>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            Nenhum histórico disponível.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection