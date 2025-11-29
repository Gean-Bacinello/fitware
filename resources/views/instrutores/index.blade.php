{{--
=====================================================================
ARQUIVO: resources/views/instrutores/index.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe o os instrutores cadastrados.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/instrutores/index-instrutores.css
    - Rota: Instrutores.index
    - Controller: InstrutorController.php
=====================================================================
--}}


@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/instrutores/index-instrutores.css') }}">
@endpush

@section('title', 'Instrutores')

@section('content')
    <div class="container mt-5">
        <div class="main-card">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="header-title mb-0">Instrutores</h2>
                <a href="{{ route('instrutores.create') }}" class="btn btn-custom-add">Adicionar<i class="bi bi-plus"></i></a>
            </div>

            {{-- Formulário de busca --}}
            <form action="{{ route('instrutores.index') }}" method="GET">
                <div class="d-flex justify-content-end mb-3">
                    <div class="input-group" style="max-width: 250px;">
                        <input type="text" class="form-control" name="search" placeholder="Procurar instrutor..."
                            value="{{ request('search') }}">
                        <button class="btn btn-custom-dark-grey" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                @if (request('search'))
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('instrutores.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x"></i> Limpar busca
                        </a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Ativo</th>
                            <th scope="col">Contato</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($instrutores as $instrutor)
                            <tr>
                               <td>
                                    <span class="badge bg-secondary">#{{ $instrutor->id }}</span>
                                </td>
                                <td>{{ $instrutor->name }}</td>
            
                                 <td>
                                    @if (strtolower($instrutor->status) == 'ativo')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Ativo
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Inativo
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $instrutor->instrutorinformacoes?->telefone }}</td>
                                <td>
                                    <a href="{{ route('instrutores.edit', $instrutor->id) }}">
                                        <button class="btn btn-sm btn-custom-edit btn-table"><i class="bi bi-pencil">
                                                Editar</i> </button></a>

                                    <a href="{{ route('instrutores.show', $instrutor->id) }}">
                                        <button class="btn btn-sm  btn-custom-show btn-table"> <i class="bi bi-eye">
                                                Ver</i></button></a>

                                    <form action="{{ route('instrutores.destroy', $instrutor) }}" method="POST"
                                        style="display: inline;">
                                        @method('DELETE')
                                        @csrf

                                        <button type="submit" class="btn btn-custom-view btn-table"
                                            onclick="return confirm('Tem Certeza que Deseja Apagar!')"><i
                                                class="bi bi-trash"> Apagar</i></button>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Mostrando {{ $instrutores->firstItem() }} a {{ $instrutores->lastItem() }} de
                    {{ $instrutores->total() }} Instrutore(s)
                </div>
                <div>
                    {{ $instrutores->links('pagination::simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
