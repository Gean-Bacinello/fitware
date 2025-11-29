<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exercicio; 
use App\Models\ClienteInformacoes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class Dashboard extends Controller
{
    
    /** ------------------------------------------------------------------------------------------------
     * EXIBE o dashboard principal com KPIs e gráficos analíticos.                                     *                                                                           *
     * Obtém os dados numéricos para os Cards de KPI (Totais e Médias).                                *
     * Obtém os dados formatados para os gráficos (Linha, Barra e Pizza).                              *
     * Consolida e envia todas as variáveis para a View de exibição.                                   *
     * -------------------------------------------------------------------------------------------------
     */
    public function index()
    {
        // --- 1. DADOS DE KPI (Cards) ---
        $kpiData = $this->getKpiData();

        // --- 2. DADOS DOS GRÁFICOS ---
        $newClientsChart = $this->getNewClientsChartData();
        $newExercisesChart = $this->getNewExercisesChartData();
        $genderChart = $this->getGenderDistributionData();
        $ageChart = $this->getAgeRangeData();
        $muscleGroupChart = $this->getMuscleGroupData();
        $clientStatusChart = $this->getClientStatusData();

        // --- 3. Envia os dados para a view ---
        return view('dashboard.index', [
            // KPIs
            'totalClientes' => $kpiData['totalClientes'],
            'totalExercicios' => $kpiData['totalExercicios'],
            'mediaPeso' => $kpiData['mediaPeso'],

            // Gráfico 1: Novos Clientes
            'newClientsLabels' => $newClientsChart['labels'],
            'newClientsData' => $newClientsChart['data'],

            // Gráfico 2: Novos Exercícios
            'newExercisesLabels' => $newExercisesChart['labels'],
            'newExercisesData' => $newExercisesChart['data'],

            // Gráfico 3: Distribuição por Sexo
            'genderLabels' => $genderChart['labels'],
            'genderData' => $genderChart['data'],

            // Gráfico 4: Faixa Etária
            'ageLabels' => $ageChart['labels'],
            'ageData' => $ageChart['data'],

            // Gráfico 5: Grupos Musculares
            'muscleGroupLabels' => $muscleGroupChart['labels'],
            'muscleGroupData' => $muscleGroupChart['data'],

            // Gráfico 6: Status dos Clientes (Ativo/Inativo) 
            'clientStatusLabels' => $clientStatusChart['labels'],
            'clientStatusData' => $clientStatusChart['data'],
        ]);
    }

    // =================================================================
    // MÉTODOS PRIVADOS PARA BUSCAR OS DADOS
    // =================================================================

    /** ------------------------------------------------------------------------------------------------
     * BUSCA e calcula os totais para os Cards (KPIs) do topo da página.                               *                                                                            *
     * Conta o total de usuários do tipo 'cliente'.                                                    *
     * Conta o total de exercícios cadastrados.                                                        *
     * Calcula a média de peso dos clientes, formatando para 1 casa decimal.                           *
     * -------------------------------------------------------------------------------------------------
     */
    private function getKpiData(): array
    {
        return [
            'totalClientes' => User::where('tipo_usuario', 'cliente')->count(),
            'totalExercicios' => Exercicio::count(),
            'mediaPeso' => number_format(ClienteInformacoes::avg('peso') ?? 0, 1),
        ];
    }

   /** ------------------------------------------------------------------------------------------------
     * GERA uma lista cronológica dos últimos 12 meses para os eixos dos gráficos.                    *                                                                            *
     * Itera 12 vezes retrocedendo a partir da data atual.                                            *
     * Formata a data para obter a chave de comparação e o rótulo de exibição.                        *
     * Retorna um array associativo com os meses.                                                     *
     * -------------------------------------------------------------------------------------------------
     */
    private function getMonthList(): array
    {
        $meses = [];
        for ($i = 11; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $meses[$mes->format('m/Y')] = $mes->format('M/y');
        }
        return $meses;
    }

    /** ------------------------------------------------------------------------------------------------
     * COMPILA os dados de novos clientes registrados nos últimos 12 meses.                            *                                                                            *
     * Obtém a lista de meses base.                                                                    *
     * Busca clientes criados no último ano e agrupa por mês.                                          *
     * Mapeia os totais para garantir que meses sem registro retornem zero.                            *
     * -------------------------------------------------------------------------------------------------
     */
    private function getNewClientsChartData(): array
    {
        $meses = $this->getMonthList();

        $clientesPorMes = User::where('tipo_usuario', 'cliente')
            ->where('created_at', '>=', now()->subYear())
            ->get()
            ->groupBy(fn ($user) => Carbon::parse($user->created_at)->format('m/Y'));

        $chartLabels = [];
        $chartData = [];
        foreach ($meses as $chaveMes => $labelMes) {
            $chartLabels[] = $labelMes;
            $chartData[] = $clientesPorMes->get($chaveMes, collect())->count();
        }

        return ['labels' => $chartLabels, 'data' => $chartData];
    }

   /** ------------------------------------------------------------------------------------------------
     * COMPILA os dados de novos exercícios cadastrados nos últimos 12 meses.                          *                                                                            *
     * Obtém a lista de meses base.                                                                    *
     * Busca exercícios criados no último ano e agrupa por mês.                                        *
     * Mapeia os totais alinhando com os labels de meses.                                              *
     * -------------------------------------------------------------------------------------------------
     */
    private function getNewExercisesChartData(): array
    {
        $meses = $this->getMonthList();

        $exerciciosPorMes = Exercicio::where('created_at', '>=', now()->subYear())
            ->get()
            ->groupBy(fn ($exercicio) => Carbon::parse($exercicio->created_at)->format('m/Y'));

        $chartLabels = [];
        $chartData = [];
        foreach ($meses as $chaveMes => $labelMes) {
            $chartLabels[] = $labelMes;
            $chartData[] = $exerciciosPorMes->get($chaveMes, collect())->count();
        }

        return ['labels' => $chartLabels, 'data' => $chartData];
    }

    /** ------------------------------------------------------------------------------------------------
     * AGREGA os dados demográficos dos clientes por Sexo.                                             *                                                                           *
     * Seleciona e conta os registros agrupando pelo campo sexo.                                       *
     * Filtra valores nulos ou vazios.                                                                 *
     * Formata os labels (primeira letra maiúscula) e extrai os totais.                                *
     * -------------------------------------------------------------------------------------------------
     */
    private function getGenderDistributionData(): array
    {
        $data = ClienteInformacoes::select('sexo', DB::raw('count(*) as total'))
            ->whereNotNull('sexo')
            ->where('sexo', '!=', '') // Ignora nulos ou vazios
            ->groupBy('sexo')
            ->get();

        return [
            'labels' => $data->pluck('sexo')->map(fn ($s) => ucfirst($s)),
            'data' => $data->pluck('total'),
        ];
    }

    /** ------------------------------------------------------------------------------------------------
     * CLASSIFICA os clientes em faixas etárias pré-definidas.                                         *                                                                            *
     * Busca todas as datas de nascimento cadastradas.                                                 *
     * Define os "buckets" (faixas) de idade iniciando em zero.                                        *
     * Itera sobre cada cliente, calcula a idade e incrementa a faixa correspondente.                  *
     * -------------------------------------------------------------------------------------------------
     */
    private function getAgeRangeData(): array
    {
        $clientesInfo = ClienteInformacoes::whereNotNull('data_nascimento')->pluck('data_nascimento');

        $faixas = [
            'Menos de 18' => 0,
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46+' => 0,
        ];

        foreach ($clientesInfo as $dataNascimento) {
            $idade = Carbon::parse($dataNascimento)->age;
            if ($idade < 18) $faixas['Menos de 18']++;
            elseif ($idade <= 25) $faixas['18-25']++;
            elseif ($idade <= 35) $faixas['26-35']++;
            elseif ($idade <= 45) $faixas['36-45']++;
            else $faixas['46+']++;
        }

        return ['labels' => array_keys($faixas), 'data' => array_values($faixas)];
    }

   /** ------------------------------------------------------------------------------------------------
     * LISTA os grupos musculares mais frequentes nos exercícios.                                      *                                                                            *
     * Agrupa e conta exercícios por grupo muscular, ignorando nulos.                                  *
     * Ordena de forma decrescente pelo total.                                                         *  
     * Limita aos top 7 resultados para otimizar a visualização do gráfico.                            *
     * -------------------------------------------------------------------------------------------------
     */
    private function getMuscleGroupData(): array
    {
        $data = Exercicio::select('grupo_muscular', DB::raw('count(*) as total'))
            ->whereNotNull('grupo_muscular')
            ->where('grupo_muscular', '!=', '')
            ->groupBy('grupo_muscular')
            ->orderBy('total', 'desc')
            ->limit(7) // Limita aos 7 maiores para o gráfico de pizza não ficar poluído
            ->get();

        return [
            'labels' => $data->pluck('grupo_muscular'),
            'data' => $data->pluck('total'),
        ];
    }


    /** ------------------------------------------------------------------------------------------------
     * CONTA clientes ativos e inativos para gráfico de pizza.                                         *
     * Busca usuários do tipo 'cliente' e agrupa pelo campo 'status'.                                  *
     * Retorna labels ("Ativo", "Inativo") e totais para visualização.                                 *
     * -------------------------------------------------------------------------------------------------
     */
    private function getClientStatusData(): array
    {
        // Conta clientes por status
        $data = User::where('tipo_usuario', 'cliente')
            ->select('status', DB::raw('count(*) as total'))
            ->whereNotNull('status')
            ->groupBy('status')
            ->get();
        
        // Formata os labels
        $labels = $data->pluck('status')->map(function($status) {
            return $status === 'ativo' ? 'Ativo' : 'Inativo';
        });
        
        return [
            'labels' => $labels,
            'data' => $data->pluck('total'),
        ];
    }
}