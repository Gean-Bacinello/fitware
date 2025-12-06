<?php

namespace App\Http\Controllers;

use App\Models\Exercicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExercicioController extends Controller
{

    /** ------------------------------------------------------------------------------------------------
     * LISTA os exercícios cadastrados, aplicando filtros de visibilidade e busca.                     *                                                                             *
     * Captura ID do usuário para verificação de permissões.                                           *
     * Filtra exercícios: exibe públicos OU criados pelo próprio usuário.                              *
     * Aplica filtros opcionais: Busca (Nome/ID), Grupo Muscular e Divisão.                            *
     * Adiciona contagem de treinos vinculados e ordena por data de criação.                           *
     * Pagina os resultados (5 por página) e retorna a View.                                           *
     * -------------------------------------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $usuario_id = Auth::id();

        $query = Exercicio::query();

        $query->where(function ($q) use ($usuario_id) {
            $q->where('visibilidade', 'publico')
                ->orWhere('usuario_criador_id', $usuario_id);
        });

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('nome_exercicio', 'like', '%' . $searchTerm . '%')
                    ->orWhere('id', '=', $searchTerm);
            });
        }

        if ($request->filled('grupo_muscular')) {
            
            $query->where('grupo_muscular', $request->grupo_muscular);
        }

       
       
        $exercicios = $query->withCount('treinos') // Adiciona a contagem de treinos
            ->latest()
            ->paginate(5);

        return view('exercicios.index', compact('exercicios'));
    }



    /** ------------------------------------------------------------------------------------------------
     * EXIBE o formulário para cadastro de um novo exercício.                                          *                                                                             *
     * Retorna a View 'exercicios.create'.                                                             *
     * -------------------------------------------------------------------------------------------------
     */
    public function create()
    {
        return view('exercicios.create');
    }

    /** ------------------------------------------------------------------------------------------------
     * ARMAZENA um novo exercício no banco de dados.                                                   *                                                                            *
     * Valida os dados obrigatórios e opcionais (incluindo imagem).                                    *
     * Vincula o exercício ao ID do usuário autenticado.                                               *
     * Gerencia o upload da imagem (se enviada) para o disco público.  ( storage -> app -> public. )   *                            *
     * Cria o registro no banco de dados.                                                              *
     * Redireciona para a listagem com mensagem de sucesso.                                            *
     * -------------------------------------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome_exercicio' => 'required|string|max:150',
            'grupo_muscular' => 'nullable|string|in:peito,costas,quadriceps,posterior,gluteo,panturrilha,ombro,biceps,triceps,abdomen,antebraco,adutores,abdutores,trapezio',
            'visibilidade'   => 'required|in:publico,privado',
            'observacao'     => 'nullable|string|max:200',
            'imagem'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $dados = $request->all();
        $dados['usuario_criador_id'] = Auth::id();

        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('exercicios_imagens', 'public');
            $dados['imagem_url'] = $path;
        }
        Exercicio::create($dados);
        return redirect()->route('exercicios.index')->with('success', 'Exercício cadastrado com sucesso!');
    }

  /** ------------------------------------------------------------------------------------------------
     * EXIBE o formulário para edição de um exercício existente.                                     *                                                                           *
     * Verifica permissão: Bloqueia acesso se privado e não pertencer ao usuário.                    *
     * Retorna a View 'exercicios.edit' com os dados do exercício.                                   *
     * -----------------------------------------------------------------------------------------------
     */
    public function edit(Exercicio $exercicio)
    {
        if ($exercicio->visibilidade == 'privado' && $exercicio->usuario_criador_id != Auth::id()) {
            abort(403, 'Acesso não autorizado');
        }
        return view('exercicios.edit', compact('exercicio'));
    }

    /** ---------------------------------------------------------------------------------------------
     * ATUALIZA os dados de um exercício específico.                                                *                                                                             *
     * Verifica autorização de edição (propriedade do registro).                                    *
     * Valida os novos dados submetidos.                                                            *
     * Gerencia substituição de imagem: remove antiga e salva nova (se houver).                     *
     * Persiste as alterações no banco de dados.                                                    *
     * Redireciona para a listagem com mensagem de sucesso.                                         *
     * ----------------------------------------------------------------------------------------------
     */
    public function update(Request $request, Exercicio $exercicio)
    {
        if ($exercicio->visibilidade == 'privado' && $exercicio->usuario_criador_id != Auth::id()) {
            abort(403);
        }
        $request->validate([
            'nome_exercicio' => 'required|string|max:150',
            'grupo_muscular' => 'nullable|string|in:peito,costas,quadriceps,posterior,gluteo,panturrilha,ombro,biceps,triceps,abdomen,antebraco,adutores,abdutores,trapezio',
            'visibilidade'   => 'required|in:publico,privado',
            'observacao'     => 'nullable|string|max:200',
            'imagem'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        $dados = $request->all();

        if ($request->hasFile('imagem')) {
            if ($exercicio->imagem_url) {
                Storage::disk('public')->delete($exercicio->imagem_url);
            }
            $path = $request->file('imagem')->store('exercicios_imagens', 'public');
            $dados['imagem_url'] = $path;
        }
        $exercicio->update($dados);
        return redirect()->route('exercicios.index')->with('success', 'Exercício atualizado com sucesso!');
    }

    /** ---------------------------------------------------------------------------------------------
     * EXCLUI um exercício do sistema.                                                              *                                                                            *
     * Verifica autorização de exclusão.                                                            *
     * Impede exclusão se o exercício estiver vinculado a treinos existentes.                       *
     * Remove a imagem associada do armazenamento (se existir).                                     *
     * Remove o registro do banco de dados.                                                         *
     * Redireciona com mensagem de sucesso ou erro.                                                 *
     * ----------------------------------------------------------------------------------------------
     */
    public function destroy(Exercicio $exercicio)
    {
        if ($exercicio->visibilidade == 'privado' && $exercicio->usuario_criador_id != Auth::id()) {
            abort(403);
        }

        // Verifica se o exercício está sendo usado em algum treino
        if ($exercicio->treinos()->exists()) {
            return redirect()
                ->route('exercicios.index')
                ->with('error', 'Não é possível excluir este exercício pois ele está sendo usado em um ou mais treinos.');
        }

        if ($exercicio->imagem_url) {
            Storage::disk('public')->delete($exercicio->imagem_url);
        }
        $exercicio->delete();
        return redirect()->route('exercicios.index')->with('success', 'Exercício excluído com sucesso!');
    }
}
