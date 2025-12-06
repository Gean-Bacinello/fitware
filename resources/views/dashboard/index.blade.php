{{--
=====================================================================
ARQUIVO: resources/views/dashboard/index.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 22/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    Esta view exibe as informaçoes gerais em forma de graficos e KPIs.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: ---
    - Rota: dashboard.index
    - Controller: Dasboard.php
=====================================================================
--}}

@extends('layouts.dashboard')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard/dashboard.css') }}">
@endpush


@section('title', 'Dashboard')

@section('content')

    {{-- =================================== --}}
    {{-- LINHA 1: KPIs (Cards de Resumo) --}}
    {{-- =================================== --}}
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Total de Clientes</h6>
                    <h2 class="display-5 fw-bold">{{ $totalClientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Total de Exercícios</h6>
                    <h2 class="display-5 fw-bold">{{ $totalExercicios }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-4"> 
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">Média de Peso (Clientes)</h6>
                    <h2 class="display-5 fw-bold">{{ $mediaPeso }} <small class="text-muted fs-4">kg</small></h2>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================== --}}
    {{-- LINHA 2: Gráficos de Novos Clientes --}}
    {{-- =================================== --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Novos Clientes (Últimos 12 Meses)</h4>
        </div>
        <div class="card-body" style="min-height: 400px;">
            
            <canvas id="graficoNovosClientes"
                data-labels='@json($newClientsLabels)'
                data-data='@json($newClientsData)'>
            </canvas>
        </div>
    </div>

    {{-- ======================================--}}
    {{-- LINHA 3: Gráficos de Novos Exercícios --}}
    {{-- ======================================--}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h4 class="mb-0">Novos Exercícios (Últimos 12 Meses)</h4>
        </div>
        <div class="card-body" style="min-height: 400px;">
            <canvas id="graficoNovosExercicios"
                data-labels='@json($newExercisesLabels)'
                data-data='@json($newExercisesData)'>
            </canvas>
        </div>
    </div>

    {{-- ===================================--}}
    {{-- LINHA 4: Demografia (Sexo e Idade) --}}
    {{-- ===================================--}}
    <div class="row mt-4">
        {{-- Gráfico de Sexo (Pizza) --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Distribuição por Sexo</h4>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    <canvas id="graficoSexo"
                        data-labels='@json($genderLabels)'
                        data-data='@json($genderData)'>
                    </canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Faixa Etária (Barras) --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Distribuição por Faixa Etária</h4>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    <canvas id="graficoIdade"
                        data-labels='@json($ageLabels)'
                        data-data='@json($ageData)'>
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================== --}}
    {{-- LINHA 5: Status dos Clientes        --}}
    {{-- =================================== --}}
    <div class="row mt-4 justify-content-center">
        {{-- Gráfico de Status (Pizza) --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Status dos Clientes</h4>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    <canvas id="graficoStatusClientes"
                        data-labels='@json($clientStatusLabels)'
                        data-data='@json($clientStatusData)'>
                    </canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =================================== --}}
    {{-- LINHA 6: Grupos Musculares          --}}
    {{-- =================================== --}}
    <div class="row mt-4 justify-content-center">
        {{-- Gráfico de Grupos Musculares (Pizza/Rosca) --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Top 7 Grupos Musculares</h4>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    <canvas id="graficoGruposMusculares"
                        data-labels='@json($muscleGroupLabels)'
                        data-data='@json($muscleGroupData)'>
                    </canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
      //Garante que o script só rode depois que o HTML estiver pronto. 
        document.addEventListener('DOMContentLoaded', function () {

            // ===================================
            // FUNÇÕES HELPER PARA CRIAR GRÁFICOS
            // ===================================

            /**
             * Busca os dados de um elemento canvas.
             * @param {string} canvasId O ID do elemento <canvas>
             * @returns {object|null} Objeto com {ctx, labels, data} ou null se não encontrado
             */
            const getChartData = (canvasId) => {
                const canvas = document.getElementById(canvasId);
                if (!canvas) {
                    console.error(`Elemento canvas com ID '${canvasId}' não encontrado.`);
                    return null;
                }
                try {
                    return {
                        ctx: canvas.getContext('2d'),
                        labels: JSON.parse(canvas.dataset.labels),
                        data: JSON.parse(canvas.dataset.data)
                    };
                } catch (e) {
                    console.error(`Erro ao fazer parse dos dados JSON do canvas '${canvasId}':`, e);
                    return null;
                }
            };

            /**
             * Cria um gráfico de Barras.
             * @param {object} chartInfo O objeto retornado por getChartData
             * @param {string} label O rótulo para o dataset
             */
            const createBarChart = (chartInfo, label) => {
                if (!chartInfo) return;

                new Chart(chartInfo.ctx, {
                    type: 'bar',
                    data: {
                        labels: chartInfo.labels,
                        datasets: [{
                            label: label,
                            data: chartInfo.data,
                            backgroundColor: 'rgba(132, 196, 65, 0.5)',
                            borderColor: 'rgba(132, 196, 65, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 } // Garante que o eixo Y não tenha casas decimais
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Esconde a legenda para gráficos de barra simples
                            }
                        }
                    }
                });
            };

             /**
             * Cria um gráfico de Pizza (Pie).
             * @param {object} chartInfo O objeto retornado por getChartData
             * @param {string} type 'pie' ou 'doughnut' (rosca)
             */
            const createPieChart = (chartInfo, type = 'pie') => {
                if (!chartInfo) return;

                new Chart(chartInfo.ctx, {
                    type: type,
                    data: {
                        labels: chartInfo.labels,
                        datasets: [{
                            data: chartInfo.data,
                            backgroundColor: [ // Paleta de cores para gráficos de pizza
                                'rgba(132, 196, 65, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)',
                                'rgba(255, 99, 132, 0.7)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top', // Exibe a legenda no topo
                            }
                        }
                    }
                });
            };

            const createStatusPieChart = (chartInfo) => {
                if (!chartInfo) return;
                
                new Chart(chartInfo.ctx, {
                    type: 'pie',
                    data: {
                        labels: chartInfo.labels,
                        datasets: [{
                            data: chartInfo.data,
                            backgroundColor: [
                                'rgba(132, 196, 65, 0.7)', // Verde para Ativo
                                'rgba(196, 65,  65, 0.7)'  // Vermelho para Inativo
                                
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }  
                        }
                    }
                });
            };      

            // ===================================
            // INICIALIZAÇÃO DE TODOS OS GRÁFICOS
            // ===================================

            // Gráficos de Barra
            createBarChart(getChartData('graficoNovosClientes'), 'Novos Clientes');
            createBarChart(getChartData('graficoNovosExercicios'), 'Novos Exercícios');
            createBarChart(getChartData('graficoIdade'), 'Qtd. Clientes');

            // Gráficos de Pizza/Rosca
            createPieChart(getChartData('graficoSexo'), 'pie');
            createPieChart(getChartData('graficoGruposMusculares'), 'doughnut'); 
            createStatusPieChart(getChartData('graficoStatusClientes'));

        });
    </script>
@endpush