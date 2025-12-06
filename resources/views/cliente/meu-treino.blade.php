{{--
=====================================================================
ARQUIVO: resources/views/cliente/meu-treino.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/10/2025
ÚLTIMA MODIFICAÇÃO: 05/12/2025
VERSÃO: 1.2 (CORREÇÃO FINAL - Agrupamento)
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
   <link rel="stylesheet" href="{{ asset('css/treinos/show-treinos.css') }}">
@endpush

@section('title', 'Meu Treino')

@section('content')
<div class="container mt-2">
    <div class="main-card p-2 p-md-4">

        @if($fichaAtiva)
            
            <div class="mb-4">
                <h2 class="header-title mb-1">{{ $fichaAtiva->treino->nome_treino }}</h2>
                <p class="text-muted mb-0">
                    Instrutor: <strong>{{ $fichaAtiva->instrutor->name ?? 'Não informado' }}</strong>
                </p>
            </div>

            {{-- 
               LÓGICA DE DEPURAÇÃO E AGRUPAMENTO:
               Agrupa os exercícios baseado na coluna pivot 'divisao'.
               Se 'divisao' vier vazio do banco, ele cairá no grupo "" (vazio).
            --}}
            @php
                $exerciciosAgrupados = $fichaAtiva->treino->exercicios->groupBy(function($item) {
                    // Garante que se estiver vazio, retorna um texto padrão para debug
                    return $item->pivot->divisao ?? 'SEM DIVISÃO DEFINIDA';
                })->sortKeys();
            @endphp

            <h4 class="section-title"><i class="bi bi-list-check"></i> Seus Exercícios</h4>
            
            {{-- LOOP EXTERNO: Cria uma área para cada Divisão (A, B, C...) --}}
            @foreach($exerciciosAgrupados as $divisao => $exercicios)
                
                <div class="card mb-4 border-0 shadow-sm">
                    {{-- Cabeçalho da Divisão --}}
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h3 class="m-0">
                            <span class="badge bg-custom-division px-3 py-2">
                                Divisão {{ strtoupper($divisao) }}
                            </span>
                        </h3>
                        <hr class="mt-2 mb-0 " style="opacity: 0.5;">
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
                                    {{-- LOOP INTERNO: Apenas exercícios desta letra --}}
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
            @endforeach

            @if($exerciciosAgrupados->isEmpty())
                <div class="alert alert-warning text-center">
                    Nenhum exercício encontrado neste treino.
                </div>
            @endif

        @else
            <div class="text-center py-5">
                <h3 class="text-muted">Você ainda não possui um treino</h3>
            </div>
        @endif

    </div>
</div>
@endsection