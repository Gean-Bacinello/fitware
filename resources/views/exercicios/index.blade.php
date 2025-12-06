{{--
=====================================================================
ARQUIVO: resources/views/exercicios/index.blade.php
AUTOR: Gean Correa Bacinello 
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
      Esta view exibe a biblioteca de exercícios.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: css/exercicios/exercicios-index.css
    - Rota: exercicio.index
    - Controller: ExercicioController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/exercicios/exercicios-index.css') }}">
@endpush

@section('title', 'Biblioteca de Exercícios')

@section('content')
    <div class="container mt-3">
        <div class="main-card">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="header-title mb-0">Biblioteca de Exercícios</h2>
                <a href="{{ route('exercicios.create') }}" class="btn btn-custom-add">Adicionar<i class="bi bi-plus"></i></a>
            </div>

            {{-- Exibe a mensagem de sucesso após uma operação --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ============================= --}}
            {{--     FORMULÁRIO DE FILTROS     --}}
            {{-- ============================= --}}

            <form action="{{ route('exercicios.index') }}" method="GET" class="mb-4">
                <div class="row g-3 align-items-end">

                    {{-- Filtro Grupo Muscular --}}
                    <div class="col-md-4">
                        <label for="grupo_muscular" class="form-label" style="font-weight: 500;">Grupo Muscular</label>
                        <select name="grupo_muscular" id="grupo_muscular" class="form-select">
                            <option value="">Todos os Grupos</option>
                            <option value="peito"       {{ request('grupo_muscular') == 'peito'       ? 'selected' : '' }}>Peito </option>
                            <option value="costas"      {{ request('grupo_muscular') == 'costas'      ? 'selected' : '' }}>Costas</option>
                            <option value="quadriceps"  {{ request('grupo_muscular') == 'quadriceps'  ? 'selected' : '' }}>Quadríceps</option>
                            <option value="posterior"   {{ request('grupo_muscular') == 'posterior'   ? 'selected' : '' }}>Posterior de Coxa</option>
                            <option value="gluteo"      {{ request('grupo_muscular') == 'gluteo'      ? 'selected' : '' }}>Glúteos </option>
                            <option value="panturrilha" {{ request('grupo_muscular') == 'panturrilha' ? 'selected' : '' }}>Panturrilha</option>
                            <option value="ombro"       {{ request('grupo_muscular') == 'ombro'       ? 'selected' : '' }}>Ombro </option>
                            <option value="biceps"      {{ request('grupo_muscular') == 'biceps'      ? 'selected' : '' }}>Bíceps</option>
                            <option value="triceps"     {{ request('grupo_muscular') == 'triceps'     ? 'selected' : '' }}>Tríceps</option>
                            <option value="abdomen"     {{ request('grupo_muscular') == 'abdomen'     ? 'selected' : '' }}>Abdômen</option>
                            <option value="antebraco"   {{ request('grupo_muscular') == 'antebraco'   ? 'selected' : '' }}>Antebraço</option>
                            <option value="adutores"    {{ request('grupo_muscular') == 'adutores'    ? 'selected' : '' }}>Adutores</option>
                            <option value="abdutores"   {{ request('grupo_muscular') == 'abdutores'   ? 'selected' : '' }}>Abdutores</option>
                            <option value="trapezio"    {{ request('grupo_muscular') == 'trapezio'    ? 'selected' : '' }}>Trapézio</option>
                        </select>
                    </div>


                    {{-- Filtro Search (ID ou Nome) --}}
                    <div class="col-md-4">
                        <label for="search" class="form-label" style="font-weight: 500;">Buscar por ID ou Nome</label>
                        <input type="text" class="form-control" name="search" id="search"
                            placeholder="Procurar exercicio..." value="{{ request('search') }}">
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="col-md-4 d-flex">
                        <button class="btn btn-custom-dark-grey w-100" type="submit">
                            <i class="bi bi-search"></i> Filtrar
                        </button>

                        {{-- Mostra o botão de Limpar APENAS se algum filtro estiver ativo --}}
                        @if (request()->filled('search') || request()->filled('grupo_muscular') || request()->filled('divisao'))
                            <a href="{{ route('exercicios.index') }}" class="btn btn-outline-secondary ms-2"
                                title="Limpar Filtros">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- ============================== --}}
            {{--   FIM FORMULÁRIO DE FILTROS    --}}
            {{-- ============================== --}}


            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-header-custom">
                        <tr>
                            <th scope="col" style="width: 10%;">Imagem</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Grupo Muscular</th>
                            <th scope="col">Visibilidade</th>
                            <th scope="col" style="width: 20%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exercicios as $exercicio)
                           
                            <tr>
                                <td>
                                    @if ($exercicio->imagem_url)
                                        <img src="{{ asset('storage/' . $exercicio->imagem_url) }}"
                                            alt="{{ $exercicio->nome_exercicio }}" class="img-thumbnail" width="80">
                                    @else
                                        <img src="https://placehold.co/80x80/eee/ccc?text=Sem+Foto" alt="Sem imagem"
                                            class="img-thumbnail">
                                    @endif
                                </td>
                                <td>{{ $exercicio->nome_exercicio }}</td>
                                <td>{{ $exercicio->grupo_muscular ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $exercicio->visibilidade == 'publico' ? 'bg-info text-dark' : 'bg-secondary' }}">
                                        {{ ucfirst($exercicio->visibilidade) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('exercicios.edit', $exercicio->id) }}">
                                        <button class="btn btn-sm btn-custom-edit btn-table">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                    </a>

                                    @if ($exercicio->treinos_count == 0)
                                        <form action="{{ route('exercicios.destroy', $exercicio->id) }}" method="POST"
                                            style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-custom-delete btn-table"
                                                onclick="return confirm('Tem certeza que deseja apagar este exercício?')"
                                                @if ($exercicio->usuario_criador_id != Auth::id() && $exercicio->visibilidade == 'privado') disabled @endif>
                                                <i class="bi bi-trash"></i> Apagar
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary btn-table" disabled>
                                            <i class="bi bi-trash"></i> Apagar
                                        </button>
                                        <small class="text-muted d-block" style="font-size: 0.7rem; margin-top: 2px;">
                                            (Em uso em {{ $exercicio->treinos_count }} treino(s))
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
    
                            <tr>
                                <td colspan="5" class="text-center py-4">

                                    {{-- Verifica se a lista está vazia POR CAUSA dos filtros --}}
                                    @if (request()->filled('search') || request()->filled('grupo_muscular') || request()->filled('divisao'))
                                        Nenhum exercício encontrado para os filtros aplicados.
                                        <a href="{{ route('exercicios.index') }}">Limpar filtros</a>
                                    @else
                                        Nenhum exercício encontrado.
                                        <a href="{{ route('exercicios.create') }}">Cadastre o primeiro!</a>
                                    @endif

                                </td>
                            </tr>
       
                        @endforelse 
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Mostrando {{ $exercicios->firstItem() }} a {{ $exercicios->lastItem() }} de
                    {{ $exercicios->total() }} Exercicio(s)
                </div>
                <div>
                    {{ $exercicios->withQueryString()->links('pagination::simple-bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
