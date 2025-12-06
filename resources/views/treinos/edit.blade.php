{{--
=====================================================================
ARQUIVO: resources/views/treinos/edit.blade.php
AUTOR: Gean Corrêa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 09/10/2025
VERSÃO: 1.1 (Com Campo de Divisão Dinâmico)
=====================================================================
DESCRIÇÃO:
   Exibe a view para edição de um treino existente.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: /public/css/treinos/edit-treinos.css
    - Rota: treinos.edit
    - Controller: TreinoController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/treinos/edit-treinos.css') }}">
@endpush

@section('title', 'Editar Treino')

@section('content')
<div class="container mt-2">
    <div class="main-card">
        
        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="header-title mb-1">Editar Treino</h2>
                <p class="text-muted mb-0">
                    Cliente: <strong>{{ $ficha->cliente->name }}</strong> |
                    Ficha criada em: <strong>{{ $ficha->data_atribuicao->format('d/m/Y') }}</strong>
                </p>
            </div>
        </div>

        {{-- Alertas de Erro e Feedback --}}
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

        <form action="{{ route('treinos.update', $ficha->id) }}" method="POST" id="formEditarTreino">
            @csrf
            @method('PUT')

            {{-- Informações Básicas --}}
            <h4 class="section-title">Informações Básicas</h4>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="nome_treino" class="form-label">Nome do Treino *</label>
                    <input type="text" class="form-control @error('nome_treino') is-invalid @enderror" id="nome_treino" name="nome_treino" value="{{ old('nome_treino', $ficha->treino->nome_treino) }}" required maxlength="150">
                    @error('nome_treino')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3" maxlength="1000" placeholder="Descreva o objetivo ou observações gerais do treino...">{{ old('descricao', $ficha->treino->descricao) }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Seção de Exercícios --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="section-title mb-0">Exercícios</h4>
                <button type="button" class="btn btn-custom-add btn-sm" onclick="adicionarExercicio()">
                    <i class="bi bi-plus"></i> Adicionar Exercício
                </button>
            </div>
            <div id="exercicios-container" class="mb-3 d-grid gap-3">
                {{-- Exercícios existentes são carregados aqui via JavaScript --}}
            </div>

            <hr class="my-4">

            {{-- Botões de Ação --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-custom-save">
                    <i class="bi bi-check-circle"></i> Salvar Alterações
                </button>
                <a href="{{ route('treinos.show', $ficha->id) }}" class="btn btn-custom-dark-grey">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const todosExercicios = @json($exercicios);
    // Dados vindos do banco (incluindo pivot com a divisão antiga)
    const exerciciosExistentes = @json($ficha->treino->exercicios);
    let exercicioIndex = 0;

    function adicionarExercicio(exercicioData = null) {
        const container = document.getElementById('exercicios-container');
        const alerta = document.getElementById('alerta-sem-exercicios');
        if (alerta) {
            alerta.remove();
        }

        // Gera as opções do Select de Exercícios
        let optionsHtml = '<option value="">-- Selecione --</option>';
        todosExercicios.forEach(exercicio => {
            const selected = exercicioData && exercicio.id === exercicioData.id ? 'selected' : '';
            optionsHtml += `<option value="${exercicio.id}" ${selected}>${exercicio.nome_exercicio}</option>`;
        });

        // Recupera dados existentes (pivot) ou deixa vazio
        const divisao = exercicioData?.pivot?.divisao || ''; // <--- RECUPERA A DIVISÃO
        const series = exercicioData?.pivot?.series || '';
        const repeticoes = exercicioData?.pivot?.repeticoes || '';
        const carga = exercicioData?.pivot?.carga || '';
        const observacoes = exercicioData?.pivot?.observacoes || '';

        // Template HTML da linha
        const novoExercicioHtml = `
            <div class="exercicio-bloco" id="exercicio-bloco-${exercicioIndex}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Exercício ${exercicioIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-custom-delete" onclick="removerExercicio(${exercicioIndex})" title="Remover exercício">
                        <i class="bi bi-trash"></i> Remover
                    </button>
                </div>
                <div class="row g-3">
                    
                    {{-- Select do Exercício --}}
                    <div class="col-md-12">
                        <label class="form-label small">Exercício *</label>
                        <select name="exercicios[${exercicioIndex}][id]" class="form-select" required>
                            ${optionsHtml}
                        </select>
                    </div>

                    {{-- NOVO CAMPO: DIVISÃO --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Divisão *</label>
                        <select name="exercicios[${exercicioIndex}][divisao]" class="form-select" required>
                            <option value="" disabled ${!divisao ? 'selected' : ''}>--</option>
                            <option value="A" ${divisao === 'A' ? 'selected' : ''}>A</option>
                            <option value="B" ${divisao === 'B' ? 'selected' : ''}>B</option>
                            <option value="C" ${divisao === 'C' ? 'selected' : ''}>C</option>
                            <option value="D" ${divisao === 'D' ? 'selected' : ''}>D</option>
                            <option value="E" ${divisao === 'E' ? 'selected' : ''}>E</option>
                            <option value="F" ${divisao === 'F' ? 'selected' : ''}>F</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label small">Séries</label>
                        <input type="text" name="exercicios[${exercicioIndex}][series]" class="form-control" placeholder="Ex: 3" value="${series}" maxlength="10">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Repetições</label>
                        <input type="text" name="exercicios[${exercicioIndex}][repeticoes]" class="form-control" placeholder="Ex: 10-12" value="${repeticoes}" maxlength="20">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small">Carga</label>
                        <input type="text" name="exercicios[${exercicioIndex}][carga]" class="form-control" placeholder="Ex: 20kg" value="${carga}" maxlength="20">
                    </div>
                    {{-- Aumentei a obs para ocupar o resto da linha --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label small">Obs.</label>
                        <input type="text" name="exercicios[${exercicioIndex}][observacoes]" class="form-control" placeholder="Ex: Cadência" value="${observacoes}" maxlength="255">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', novoExercicioHtml);
        exercicioIndex++;
    }

    function removerExercicio(index) {
        const exercicioBloco = document.getElementById(`exercicio-bloco-${index}`);
        if (exercicioBloco) {
            exercicioBloco.style.transition = 'opacity 0.3s, transform 0.3s';
            exercicioBloco.style.opacity = '0';
            exercicioBloco.style.transform = 'scale(0.95)';
            setTimeout(() => {
                exercicioBloco.remove();
                renumerarExercicios();
                const container = document.getElementById('exercicios-container');
                if (!container.querySelector('.exercicio-bloco')) {
                    container.innerHTML = `<div class="alert alert-light text-center border" id="alerta-sem-exercicios">Nenhum exercício no treino. Adicione pelo menos um!</div>`;
                }
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

    // Carrega os exercícios existentes ao abrir a página
    window.addEventListener('load', function() {
        if (exerciciosExistentes && exerciciosExistentes.length > 0) {
            exerciciosExistentes.forEach(exercicio => {
                adicionarExercicio(exercicio);
            });
        } else {
            // Se não houver exercícios (erro de dados), adiciona um vazio
            adicionarExercicio();
        }
    });

    document.getElementById('formEditarTreino').addEventListener('submit', function(e) {
        const container = document.getElementById('exercicios-container');
        const temExercicios = container.querySelector('.exercicio-bloco');
        if (!temExercicios) {
            e.preventDefault();
            alert('Adicione pelo menos um exercício ao treino!');
            return false;
        }
    });
</script>
@endsection