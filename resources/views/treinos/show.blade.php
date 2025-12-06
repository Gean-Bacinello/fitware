{{--
=====================================================================
ARQUIVO: resources/views/treinos/show.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 05/12/2025
VERSÃO: 1.1 (Visualização agrupada por divisão)
=====================================================================
DESCRIÇÃO:
   Exibe os detalhes de uma ficha para o instrutor.
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
                <div class="mt-3 mb-5">
                     <a href="{{ route('treinos.edit', $ficha->id) }}" class="btn btn-custom-edit">
                        <i class="bi bi-pencil"></i> Editar Treino
                    </a>
                </div>

                {{-- ============================ --}}
                {{-- LISTA DE EXERCÍCIOS AGRUPADA --}}
                {{-- =============================--}}
                
                @php
                    // Agrupa os exercícios baseado na coluna pivot 'divisao'
                    $exerciciosAgrupados = $ficha->treino->exercicios->groupBy(function($item) {
                        return $item->pivot->divisao ?? 'GERAL';
                    })->sortKeys();
                @endphp

                <h4 class="section-title"><i class="bi bi-list-check"></i> Exercícios do Treino</h4>

                @forelse($exerciciosAgrupados as $divisao => $exercicios)
                    <div class="card mb-4 border-0 shadow-sm">
                        {{-- Cabeçalho da Divisão --}}
                        <div class="card-header bg-transparent border-0 pt-3 pb-0">
                            <h3 class="m-0">
                                <span class="badge bg-custom-division px-3 py-2">
                                    Divisão {{ strtoupper($divisao) }}
                                </span>
                            </h3>
                            <hr class="mt-2 mb-0" style="opacity: 0.5;">
                        </div>

                        {{-- Tabela da Divisão --}}
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="ps-4">Exercício</th>
                                            <th scope="col">Séries</th>
                                            <th scope="col">Repetições</th>
                                            <th scope="col">Carga</th>
                                            <th scope="col">Obs.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exercicios as $exercicio)
                                            <tr>
                                                <td class="ps-4">
                                                    <strong>{{ $exercicio->nome_exercicio }}</strong>
                                                    @if($exercicio->grupo_muscular)
                                                        <br><small class="text-muted">{{ $exercicio->grupo_muscular }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $exercicio->pivot->series ?? '-' }}</td>
                                                <td>{{ $exercicio->pivot->repeticoes ?? '-' }}</td>
                                                <td>{{ $exercicio->pivot->carga ?? '-' }}</td>
                                                <td><small>{{ $exercicio->pivot->observacoes ?? '-' }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center py-4 border">
                        Nenhum exercício cadastrado neste treino.
                    </div>
                @endforelse

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