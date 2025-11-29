{{--
=====================================================================
ARQUIVO: resources/views/treinos/listarClientes.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 09/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe a lista de clientes para gerenciamento de treinos.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/treinos/listar-clientes.css
    - Rota: treinos.listarClientes
    - Controller: TreinoController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
       <link rel="stylesheet" href="{{ asset('css/treinos/listar-clientes.css') }}">
@endpush

@section('title', 'Gerenciar Treinos')

@section('content')
<div class="container mt-5">
    <div class="main-card">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="header-title mb-0">Gerenciar Treinos dos Clientes</h2>
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

        {{-- Formulário de busca --}}
        <form action="{{ route('treinos.listarClientes') }}" method="GET">
            <div class="d-flex justify-content-end mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" name="search" placeholder="Procurar cliente..." value="{{ request('search') }}">
                    <button class="btn btn-custom-dark-grey" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
             @if(request('search'))
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('treinos.listarClientes') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x"></i> Limpar busca
                    </a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-header-custom">
                    <tr>
                        <th scope="col">Cliente</th>
                        <th scope="col">Status do Treino</th>
                        <th scope="col" class="text-center" style="width: 35%;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>
                                <strong>{{ $cliente->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $cliente->email }}</small>
                            </td>
                            <td>
                                @if($cliente->fichasComoCliente->isNotEmpty())
                                    @php
                                        $ultimaFicha = $cliente->fichasComoCliente->sortByDesc('data_atribuicao')->first();
                                        $totalExercicios = $ultimaFicha->treino->exercicios->count();
                                    @endphp
                                    <span class="badge bg-success">Treino Ativo</span>
                                    <br>
                                    <small class="text-muted">
                                        {{ $ultimaFicha->treino->nome_treino }} ({{ $totalExercicios }} exercício(s))
                                    </small>
                                @else
                                    <span class="badge bg-warning text-dark">Sem Treino</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($cliente->fichasComoCliente->isNotEmpty())
                                    @php $ultimaFicha = $cliente->fichasComoCliente->sortByDesc('data_atribuicao')->first(); @endphp
                                    
                                    <a href="{{ route('treinos.show', $ultimaFicha->id) }}" class="btn btn-sm btn-custom-show btn-table" title="Ver treino atual">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    
                                    <a href="{{ route('treinos.edit', $ultimaFicha->id) }}" class="btn btn-sm btn-custom-edit btn-table" title="Editar treino atual">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                @endif
                                
                                <a href="{{ route('treinos.create', $cliente->id) }}" class="btn btn-sm btn-custom-assign btn-table" title="Atribuir novo treino">
                                    <i class="bi bi-plus-circle"></i> 
                                    {{ $cliente->fichasComoCliente->isNotEmpty() ? 'Novo' : 'Atribuir' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                @if(request('search'))
                                    Nenhum cliente encontrado com o termo "{{ request('search') }}".
                                @else
                                    Nenhum cliente cadastrado ainda.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clientes->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                 <div class="text-muted small">
                    Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }} de {{ $clientes->total() }} Treino(s)
                </div>
                <div>
                    {{ $clientes->withQueryString()->links('pagination::simple-bootstrap-5') }}
                </div>
            </div>
        @endif
        
    </div>
</div>
@endsection