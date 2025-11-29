{{--
=====================================================================
ARQUIVO: resources/views/cliente/meu-treino.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: 03/10/2025
ÚLTIMA MODIFICAÇÃO: 10/11/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
  View para o cliente logado visualizar seu treino mais recente.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/treinos/show-treinos.css
    - Rota: meu-treino
    - Controller: MeuTreinoController.php
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

        {{-- Verifica se o cliente tem uma ficha de treino --}}
        @if($fichaAtiva)
            
            {{-- Cabeçalho --}}
            <div class="mb-4">
                <h2 class="header-title mb-1">{{ $fichaAtiva->treino->nome_treino }}</h2>
                <p class="text-muted mb-0">
                    Atribuído em: <strong>{{ $fichaAtiva->data_atribuicao->format('d/m/Y') }}</strong>
                    | Instrutor: <strong>{{ $fichaAtiva->instrutor->name ?? 'Não informado' }}</strong>
                </p>
                @if($fichaAtiva->treino->descricao)
                    <p class="mb-0 fst-italic mt-2">"{{ $fichaAtiva->treino->descricao }}"</p>
                @endif
            </div>

            {{-- Lista de Exercícios --}}
            <h4 class="section-title"><i class="bi bi-list-check"></i> Seus Exercícios</h4>
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
                        @forelse($fichaAtiva->treino->exercicios as $exercicio)
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
                                <td colspan="5" class="text-center py-3 py-md-5">
                                    Seu treino ainda não possui exercícios. Fale com seu instrutor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @else
            {{-- Mensagem para cliente sem treino --}}
            <div class="text-center py-3 py-md-5">
                <h3 class="text-muted">Você ainda não possui um treino</h3>
                <p>Assim que um instrutor atribuir sua ficha, ela aparecerá aqui.</p>
                <i class="bi bi-emoji-frown" style="font-size: 3rem; color: #ccc;"></i>
            </div>
        @endif

    </div>
</div>
@endsection