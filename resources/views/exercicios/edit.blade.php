{{--
=====================================================================
ARQUIVO: resources/views/exercicios/edit.blade.php
AUTOR: Gean Correa Bacinello 
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
     Esta view exibe o formulário para editar um exercício existente.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: css/exercicios/exercicios-form.css
    - Rota: exercicio.edit
    - Controller: ExercicioController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/exercicios/exercicios-form.css') }}">
@endpush

@section('title', 'Editar Exercício')

@section('content')
<div class="container mt-5">
    <div class="main-card">

        <h2 class="header-title mb-4">Editar Exercício: {{ $exercicio->nome_exercicio }}</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Opa!</strong> Verifique os erros abaixo.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('exercicios.update', $exercicio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- LINHA 1: Nome, Grupo Muscular e Divisão --}}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nome_exercicio" class="form-label">Nome do Exercício <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nome_exercicio') is-invalid @enderror" id="nome_exercicio" name="nome_exercicio" value="{{ old('nome_exercicio', $exercicio->nome_exercicio) }}" required>
                    @error('nome_exercicio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- =============================== --}}
                {{--      CAMPO GRUPO MUSCULAR       --}}
                {{-- =============================== --}}
                <div class="col-md-4 mb-3">
                    <label for="grupo_muscular" class="form-label">Grupo Muscular</label>
                    <select class="form-select @error('grupo_muscular') is-invalid @enderror" id="grupo_muscular" name="grupo_muscular">
                        <option value="">Selecione um grupo</option>
                        <option value="peito"       {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'peito'       ? 'selected' : '' }}>Peito</option>
                        <option value="costas"      {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'costas'      ? 'selected' : '' }}>Costas</option>
                        <option value="quadriceps"  {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'quadriceps'  ? 'selected' : '' }}>Quadríceps</option>
                        <option value="posterior"   {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'posterior'   ? 'selected' : '' }}>Posterior de Coxa</option>
                        <option value="gluteo"      {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'gluteo'      ? 'selected' : '' }}>Glúteo</option>
                        <option value="panturrilha" {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'panturrilha' ? 'selected' : '' }}>Panturrilha</option>
                        <option value="ombro"       {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'ombro'       ? 'selected' : '' }}>Ombro</option>
                        <option value="biceps"      {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'biceps'      ? 'selected' : '' }}>Bíceps</option>
                        <option value="triceps"     {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'triceps'     ? 'selected' : '' }}>Tríceps</option>
                        <option value="abdomen"     {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'abdomen'     ? 'selected' : '' }}>Abdômen</option>
                        <option value="antebraco"   {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'antebraco'   ? 'selected' : '' }}>Antebraço</option>
                        <option value="adutores"    {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'adutores'    ? 'selected' : '' }}>Adutores</option>
                        <option value="abdutores"   {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'abdutores'   ? 'selected' : '' }}>Abdutores</option> 
                        <option value="trapezio"    {{ old('grupo_muscular', $exercicio->grupo_muscular) == 'trapezio'    ? 'selected' : '' }}>Trapézio</option>
                    </select>
                     @error('grupo_muscular')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ========================== --}}
                {{--      CAMPO DIVISÃO         --}}
                {{-- ========================== --}}
                <div class="col-md-4 mb-3">
                    <label for="divisao" class="form-label">Divisão do Treino</label>
                    <select class="form-select @error('divisao') is-invalid @enderror" id="divisao" name="divisao">
                        <option value="">Selecione uma divisão</option>
                        <option value="A" {{ old('divisao', $exercicio->divisao) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('divisao', $exercicio->divisao) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('divisao', $exercicio->divisao) == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('divisao', $exercicio->divisao) == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ old('divisao', $exercicio->divisao) == 'E' ? 'selected' : '' }}>E</option>
                        <option value="F" {{ old('divisao', $exercicio->divisao) == 'F' ? 'selected' : '' }}>F</option>
                    </select>
                     @error('divisao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- LINHA 2: Observações (Largura total) --}}
            <div class="mb-3">
                <label for="observacao" class="form-label">Observações / Instruções de Execução</label>
                <textarea class="form-control  @error('observacao') is-invalid @enderror" id="observacao" name="observacao" rows="4">{{ old('observacao', $exercicio->observacao) }}</textarea>
                @error('observacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- LINHA 3: Visibilidade e Imagem --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="visibilidade" class="form-label">Visibilidade <span class="text-danger">*</span></label>
                    <select class="form-select" id="visibilidade" name="visibilidade" required>
                        <option value="publico" {{ old('visibilidade', $exercicio->visibilidade) == 'publico' ? 'selected' : '' }}>Público (visível para todos instrutores)</option>
                        <option value="privado" {{ old('visibilidade', $exercicio->visibilidade) == 'privado' ? 'selected' : '' }}>Privado (visível apenas para você)</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="imagem" class="form-label">Alterar Imagem do Exercício (Opcional)</label>
                    <input class="form-control @error('imagem') is-invalid @enderror" type="file" id="imagem" name="imagem">
                    <div class="form-text">Deixe em branco para manter a imagem atual.</div>
                    @error('imagem')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Exibição da Imagem Atual (se existir) --}}
            @if($exercicio->imagem_url)
                <div class="mb-3">
                    <label class="form-label d-block">Imagem Atual:</label>
                    <img src="{{ asset('storage/' . $exercicio->imagem_url) }}" alt="{{ $exercicio->nome_exercicio }}" class="img-thumbnail" width="150">
                </div>
            @endif

            {{-- Botões de Ação --}}
            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('exercicios.index') }}" class="btn btn-custom-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-custom-primary">Atualizar Exercício</button>
            </div>
        </form>
    </div>
</div>
@endsection