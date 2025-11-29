{{--
=====================================================================
ARQUIVO: resources/views/clientes/index.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 25/10/2025
VERSÃO: 1.1
=====================================================================
DESCRIÇÃO:
    Esta view exibe os clientes cadastrados com proteção contra
    exclusão de clientes com treinos atribuídos.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/clientes/clientes-index.css
    - Rota: clientes.index
    - Controller: ClienteController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/clientes/clientes-index.css') }}">
@endpush

@section('title', 'Clientes')

@section('content')
    <div class="container mt-5">
        <div class="main-card">

            {{-- Mensagens de Feedback --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <strong>Sucesso!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>Atenção!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i> <strong>Aviso!</strong> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="header-title mb-0">Clientes</h2>
                <a href="{{ route('clientes.create') }}" class="btn btn-custom-add">
                    Adicionar <i class="bi bi-plus"></i>
                </a>
            </div>

            {{-- Formulário de busca --}}
            <form action="{{ route('clientes.index') }}" method="GET" class="mb-4">
                
                <div class="row g-3 align-items-end">

                    {{-- Filtro de Status (Ativo/Inativo) --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label" style="font-weight: 500;">Status do Cliente</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos os Status</option>
                            <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>
                                Somente Ativos
                            </option>
                            <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>
                                Somente Inativos
                            </option>
                        </select>
                    </div>

                    {{-- Filtro de Nome ou ID --}}
                    <div class="col-md-5">
                        <label for="search" class="form-label" style="font-weight: 500;">Buscar por Nome ou ID</label>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Procurar cliente..." value="{{ request('search') }}">
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="col-md-3 d-flex">
                        
                        <button class="btn btn-custom-dark-grey w-100" type="submit">
                            <i class="bi bi-search"></i> Filtrar
                        </button>

                        {{-- Botão de limpar (condicional) --}}
                        {{-- Ele só aparece se um dos filtros estiver preenchido --}}
                        @if (request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary ms-2"
                                title="Limpar Filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Status</th>
                            <th scope="col">Treinos</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">#{{ $cliente->id }}</span>
                                </td>
                                <td>
                                    <strong>{{ $cliente->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $cliente->email }}</small>
                                </td>
                                <td>
                                    @if (strtolower($cliente->status) == 'ativo')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Ativo
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Inativo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $totalTreinos = $cliente->fichasComoCliente()->count();
                                    @endphp
                                    @if ($totalTreinos > 0)
                                        <span class="badge bg-info">
                                            <i class="bi bi-clipboard-check"></i> {{ $totalTreinos }} treino(s)
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-clipboard-x"></i> Nenhum treino
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clientes.show', $cliente->id) }}"
                                            class="btn btn-sm btn-custom-show btn-table" title="Ver detalhes">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>

                                        <a href="{{ route('clientes.edit', $cliente->id) }}"
                                            class="btn btn-sm btn-custom-edit btn-table" title="Editar cliente">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        @if ($totalTreinos > 0)
                                            {{-- Cliente possui treinos: botão desabilitado --}}
                                            <button type="button" class="btn btn-sm btn-custom-view btn-table" disabled
                                                title="Não é possível excluir: cliente possui {{ $totalTreinos }} treino(s)">
                                                <i class="bi bi-trash"></i> Apagar
                                            </button>
                                        @else
                                            {{-- Cliente SEM treinos: pode excluir --}}
                                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                                style="display: inline;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-custom-view btn-table"
                                                    title="Excluir cliente"
                                                    onclick="return confirm('Tem certeza que deseja excluir o cliente {{ $cliente->name }}?\n\nEsta ação não pode ser desfeita!')">
                                                    <i class="bi bi-trash"></i> Apagar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Nenhum cliente cadastrado ainda.</p>
                                        <a href="{{ route('clientes.create') }}" class="btn btn-custom-add">
                                            Cadastrar Primeiro Cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            <div class="d-flex justify-content-between align-items-center mt-3">

                <div class="text-muted small">
                    @if ($clientes->total() > 0)
                        Mostrando {{ $clientes->firstItem() }} a {{ $clientes->lastItem() }} de {{ $clientes->total() }}
                        cliente(s)
                    @endif
                </div>

                <div>
                    {{ $clientes->withQueryString()->links('pagination::simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
