{{--
=====================================================================
ARQUIVO: resources/views/treinos/create.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 09/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Formulário para criar um novo treino e atribuí-lo a um cliente.
    Inclui funcionalidade para adicionar exercícios dinamicamente.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/treinos/create-treinos.css'
    - Rota: treinos.create
    - Controller: TreinoController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/treinos/create-treinos.css') }}">
@endpush

@section('title', 'Atribuir Treino')

@section('content')
<div class="container mt-5">
    <div class="main-card">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="header-title mb-1">Atribuir Novo Treino</h2>
                <p class="text-muted mb-0">Cliente: <strong>{{ $cliente->name }}</strong></p>
            </div>
        </div>

        {{-- Exibição de Erros de Validação --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Atenção!</strong> Corrija os erros abaixo:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Mensagens de Sucesso/Erro da Sessão --}}
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

        <form action="{{ route('treinos.store') }}" method="POST" id="formTreino">
            @csrf
            <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

            {{-- Informações Básicas --}}
            <h4 class="section-title">Informações Básicas</h4>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nome_treino" class="form-label">Nome do Treino *</label>
                    <input type="text" class="form-control @error('nome_treino') is-invalid @enderror" id="nome_treino" name="nome_treino" value="{{ old('nome_treino', 'Treino Personalizado - ' . $cliente->name) }}" required maxlength="150">
                    @error('nome_treino')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3" maxlength="1000" placeholder="Descreva o objetivo ou observações gerais do treino...">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Seção de Exercícios --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="section-title mb-0">Exercícios</h4>
                <button type="button" class="btn btn-custom-add" onclick="adicionarExercicio()">
                    <i class="bi bi-plus"></i> Adicionar Exercício
                </button>
            </div>
            <div id="exercicios-container" class="mb-3 d-grid gap-3">
                {{-- Exercícios são adicionados aqui via JavaScript --}}
                <div class="alert alert-light text-center border" id="alerta-sem-exercicios">
                    Nenhum exercício adicionado. Clique no botão acima para começar.
                </div>
            </div>

            <hr class="my-4">

            {{-- Botões de Ação --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-custom-save">
                    <i class="bi bi-check-circle"></i> Salvar e Atribuir Treino
                </button>
                <a href="{{ route('treinos.listarClientes') }}" class="btn btn-custom-dark-grey">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const todosExercicios = @json($exercicios);
    let exercicioIndex = 0;

    function adicionarExercicio() {
        const container = document.getElementById('exercicios-container');
        const alerta = document.getElementById('alerta-sem-exercicios');
        if (alerta) {
            alerta.remove();
        }

        let optionsHtml = '<option value="" disabled selected>-- Selecione --</option>';
        todosExercicios.forEach(exercicio => {
            optionsHtml += `<option value="${exercicio.id}">${exercicio.nome_exercicio}</option>`;
        });

        const novoExercicioHtml = `
            <div class="exercicio-bloco" id="exercicio-bloco-${exercicioIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Exercício ${exercicioIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-custom-delete" onclick="removerExercicio(${exercicioIndex})" title="Remover exercício">
                        <i class="bi bi-trash"></i> Remover
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small">Exercício *</label>
                        <select name="exercicios[${exercicioIndex}][id]" class="form-select" required>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                    <label class="form-label small">Divisão *</label>
                    <select name="exercicios[${exercicioIndex}][divisao]" class="form-select" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Séries</label>
                        <input type="text" name="exercicios[${exercicioIndex}][series]" class="form-control" placeholder="Ex: 3" maxlength="10">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Repetições</label>
                        <input type="text" name="exercicios[${exercicioIndex}][repeticoes]" class="form-control" placeholder="Ex: 10-12" maxlength="20">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Carga</label>
                        <input type="text" name="exercicios[${exercicioIndex}][carga]" class="form-control" placeholder="Ex: 20kg" maxlength="20">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Obs.</label>
                        <input type="text" name="exercicios[${exercicioIndex}][observacoes]" class="form-control" placeholder="Ex: Cadência" maxlength="255">
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', novoExercicioHtml);
        exercicioIndex++;

        setTimeout(() => {
            const novoBloco = document.getElementById(`exercicio-bloco-${exercicioIndex - 1}`);
            if (novoBloco) {
                novoBloco.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    }

    function removerExercicio(index) {
        const exercicioBloco = document.getElementById(`exercicio-bloco-${index}`);
        if (exercicioBloco) {
            exercicioBloco.style.transition = 'opacity 0.3s, transform 0.3s';
            exercicioBloco.style.opacity = '0';
            exercicioBloco.style.transform = 'scale(0.95)';
            setTimeout(() => {
                exercicioBloco.remove();
                const container = document.getElementById('exercicios-container');
                if (!container.querySelector('.exercicio-bloco')) {
                    container.innerHTML = `<div class="alert alert-light text-center border" id="alerta-sem-exercicios">Nenhum exercício adicionado. Clique no botão acima para começar.</div>`;
                }
                renumerarExercicios();
            }, 300);
        }
    }

    function renumerarExercicios() {
        const blocos = document.querySelectorAll('.exercicio-bloco');
        blocos.forEach((bloco, index) => {
            const header = bloco.querySelector('h6');
            if (header) {
                header.textContent = `Exercício ${index + 1}`;
            }
        });
    }

    document.getElementById('formTreino').addEventListener('submit', function(e) {
        const container = document.getElementById('exercicios-container');
        const temExercicios = container.querySelector('.exercicio-bloco');
        if (!temExercicios) {
            e.preventDefault();
            alert('Adicione pelo menos um exercício ao treino!');
            return false;
        }
    });

    window.addEventListener('load', function() {
        if (document.querySelectorAll('.exercicio-bloco').length === 0) {
           adicionarExercicio();
        }
    });
</script>
@endsection