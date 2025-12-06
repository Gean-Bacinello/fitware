<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Treino;
use App\Models\Ficha;
use App\Models\Exercicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreinoController extends Controller
{
    /** ------------------------------------------------------------------------------------------------
     * LISTA todos os clientes e suas respectivas fichas de treino recentes.                           *                                                                           *
     * Inicia query filtrando usuários do tipo 'cliente'.                                              *
     * Carrega o relacionamento 'fichasComoCliente' (últimas atribuídas) e seus exercícios.            *
     * Aplica filtro de busca opcional (Nome, Email ou ID).                                            *
     * Pagina os resultados (5 por vez) e retorna a View.                                              *
     * -------------------------------------------------------------------------------------------------
     */
    public function listarClientes(Request $request)
    {
        $query = User::where('tipo_usuario', 'cliente')
            ->with(['fichasComoCliente' => function ($q) {
                $q->latest('data_atribuicao')->with('treino.exercicios');
            }]);

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%') 
                    ->orWhere('id', '=', $searchTerm); 
            });
        }

        $clientes = $query->paginate(5);

        return view('treinos.listar_clientes', compact('clientes'));
    }

    /** ------------------------------------------------------------------------------------------------
     * EXIBE o formulário para criação e atribuição de um novo treino.                                 *                                                                            *
     * Valida se o usuário passado na rota é realmente um cliente.                                     *
     * Busca todos os exercícios cadastrados ordenados por nome.                                       *
     * Retorna a View de criação com os dados necessários.                                             *
     * -------------------------------------------------------------------------------------------------
     */
    public function create(User $cliente)
    {

        if ($cliente->tipo_usuario !== 'cliente') {
            return redirect()
                ->route('treinos.listarClientes')
                ->with('error', 'Usuário inválido para atribuição de treino.');
        }

        $exercicios = Exercicio::orderBy('nome_exercicio')->get();

        return view('treinos.create', compact('cliente', 'exercicios'));
    }

   /** ------------------------------------------------------------------------------------------------
     * ARMAZENA um novo treino e vincula a ficha ao cliente.                                           *                                                                            *
     * Valida os dados recebidos (incluindo o array aninhado de exercícios).                           *
     * Inicia uma Transação de Banco de Dados (DB Transaction).                                        *
     * Cria o registro do Treino.                                                                      *
     * Vincula os exercícios ao treino (tabela pivô) com séries, repetições e carga.                   *
     * Cria a Ficha vinculando Cliente, Treino e Instrutor responsável.                                *
     * Confirma (Commit) a transação ou reverte (Rollback) em caso de erro.                            *
     * -------------------------------------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_treino'               => 'required|string|max:150',
            'descricao'                 => 'nullable|string|max:1000',
            'cliente_id'                => 'required|exists:users,id',
            'exercicios'                => 'required|array|min:1',
            'exercicios.*.id'           => 'required|exists:exercicios,id',
            'exercicios.*.series'       => 'nullable|string|max:10',
            'exercicios.*.repeticoes'   => 'nullable|string|max:20',
            'exercicios.*.carga'        => 'nullable|string|max:20',
            'exercicios.*.observacoes'  => 'nullable|string|max:255',
            'exercicios.*.divisao'      => 'required|string|in:A,B,C,D,E,F',
        ], [
            'exercicios.required'       => 'Adicione pelo menos um exercício ao treino.',
            'exercicios.*.id.required'  => 'Selecione um exercício válido.',
            'exercicios.*.id.exists'    => 'Exercício selecionado não existe.',
        ]);

        DB::beginTransaction();
        try {
            
            $treino = Treino::create([
                'nome_treino'        => $validated['nome_treino'],
                'descricao'          => $validated['descricao'] ?? null,
                'usuario_criador_id' => Auth::id(),
                'tipo'               => 'personalizado',
            ]);

            //Anexar exercícios ao treino
            foreach ($validated['exercicios'] as $exercicioData) {
                $treino->exercicios()->attach($exercicioData['id'], [
                    'divisao'     => $exercicioData['divisao'],
                    'series'      => $exercicioData['series'] ?? null,
                    'repeticoes'  => $exercicioData['repeticoes'] ?? null,
                    'carga'       => $exercicioData['carga'] ?? null,
                    'observacoes' => $exercicioData['observacoes'] ?? null,
                ]);
            }

            //Criar a Ficha (liga o treino ao cliente)
            Ficha::create([
                'cliente_id'      => $validated['cliente_id'],
                'treino_id'       => $treino->id,
                'instrutor_id'    => Auth::id(),
                'data_atribuicao' => now(),
                'status_ficha'    => 'ativo',
            ]);

            DB::commit();

            return redirect()
                ->route('treinos.listarClientes')
                ->with('success', 'Treino atribuído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao criar treino', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'cliente_id' => $validated['cliente_id'] ?? null,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atribuir treino. Tente novamente.');
        }
    }


    /** ------------------------------------------------------------------------------------------------
     * EXIBE o histórico completo de fichas de um cliente específico.                                  *                                                                            *
     * Busca fichas do cliente carregando Treino, Exercícios e Instrutor.                              *
     * Ordena por data de atribuição (decrescente).                                                    *
     * Pagina os resultados e retorna a View.                                                          *
     * -------------------------------------------------------------------------------------------------
     */
    public function historico(User $cliente)
    {
        $fichas = $cliente->fichasComoCliente()
            ->with(['treino.exercicios', 'instrutor'])
            ->orderBy('data_atribuicao', 'desc')
            ->paginate(10);

        return view('treinos.historico', compact('cliente', 'fichas'));
    }

    /** ------------------------------------------------------------------------------------------------
     * EXIBE os detalhes de uma ficha específica.                                                      *                                                                           *
     * Carrega os relacionamentos da Ficha (Cliente, Instrutor, Treino e Exercícios).                  *
     * Retorna a View de detalhes.                                                                     *
     * -------------------------------------------------------------------------------------------------
     */
    public function show(Ficha $ficha)
    {
        $ficha->load([
            'cliente',
            'instrutor',
            'treino.exercicios'
        ]);

        return view('treinos.show', compact('ficha'));
    }

    /** ------------------------------------------------------------------------------------------------
     * EXIBE o formulário de edição para uma ficha existente.                                          *                                                                           *
     * Carrega os dados atuais da Ficha e do Treino.                                                   *
     * Busca a lista completa de exercícios para permitir alterações.                                  *
     * Retorna a View de edição.                                                                       *
     * -------------------------------------------------------------------------------------------------
     */
    public function edit(Ficha $ficha)
    {
        $ficha->load([
            'cliente',
            'treino.exercicios'
        ]);

        $exercicios = Exercicio::orderBy('nome_exercicio')->get();

        return view('treinos.edit', compact('ficha', 'exercicios'));
    }

    /** ------------------------------------------------------------------------------------------------
     * ATUALIZA os dados de uma ficha e seus exercícios.                                               *                                                                            *
     * Valida os dados de entrada.                                                                     *
     * Inicia Transação de Banco de Dados.                                                             *
     * Atualiza informações básicas do Treino.                                                         *
     * Remove (detach) todos os exercícios antigos.                                                    *
     * Adiciona (attach) os novos exercícios com as configurações atualizadas.                         *
     * Confirma a transação e redireciona.                                                             *
     * -------------------------------------------------------------------------------------------------
     */
    public function update(Request $request, Ficha $ficha)
    {
        $validated = $request->validate([
            'nome_treino'              => 'required|string|max:150',
            'descricao'                => 'nullable|string|max:1000',
            'exercicios'               => 'required|array|min:1',
            'exercicios.*.id'          => 'required|exists:exercicios,id',
            'exercicios.*.series'      => 'nullable|string|max:10',
            'exercicios.*.repeticoes'  => 'nullable|string|max:20',
            'exercicios.*.carga'       => 'nullable|string|max:20',
            'exercicios.*.observacoes' => 'nullable|string|max:255',
            'exercicios.*.divisao'     => 'required|string|in:A,B,C,D,E,F',
        ]);

        DB::beginTransaction();
        try {
            // Atualiza o treino
            $ficha->treino->update([
                'nome_treino' => $validated['nome_treino'],
                'descricao'   => $validated['descricao'] ?? null,
            ]);

            // Redefine os exercícios
            $ficha->treino->exercicios()->detach();

            foreach ($validated['exercicios'] as $exercicioData) {
                $ficha->treino->exercicios()->attach($exercicioData['id'], [
                    'divisao'     => $exercicioData['divisao'],
                    'series'      => $exercicioData['series'] ?? null,
                    'repeticoes'  => $exercicioData['repeticoes'] ?? null,
                    'carga'       => $exercicioData['carga'] ?? null,
                    'observacoes' => $exercicioData['observacoes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('treinos.show', $ficha)
                ->with('success', 'Treino atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar treino', [
                'message' => $e->getMessage(),
                'ficha_id' => $ficha->id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar treino.');
        }
    }
}
