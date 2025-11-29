<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{

    /** ------------------------------------------------------------------------------------------------
     * LISTA os usuários do Tipo CLIENTE, gerenciando filtros e paginação.                             *
     * Etapas de execução:                                                                             *
     * Recebe a Request para capturar parâmetros de filtro.                                         *
     * Inicia query filtrando apenas pelo tipo 'cliente'.                                           *
     * Verifica e aplica filtro de BUSCA (parcial por Nome ou exata por ID).                        *
     * Verifica e aplica filtro de STATUS (ex: ativo/inativo).                                      *
     * Ordena pelos últimos criados e pagina (5 registros por vez).                                 *
     * Retorna a View 'clientes.index' com os dados.                                                                   *
     * -------------------------------------------------------------------------------------------------
     */
    public function index(Request $request) 
    {
        $query = User::query();

        $query->where('tipo_usuario', 'cliente');

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            // Agrupa a lógica de busca para não conflitar
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%') // Busca parcial no nome
                    ->orWhere('id', '=', $searchTerm);             // Busca exata no ID
            });
        }

        if ($request->filled('status')) {

            $query->where('status', $request->status);
        }

        $clientes = $query->latest()->paginate(5);

        return view('clientes.index', [
            'clientes' => $clientes
        ]);
    }


    /**--------------------------------------------------------------------------------------------------
     * Retorna a view de CADASTRO de clientes.                                                          *                                                     
     *                                                                                                  *                                                                                    
     *                                                                                                  *                                                                             
     * --------------------------------------------------------------------------------------------------
     */
    public function create()
    {
        return view('clientes.create');
    }



    /**-------------------------------------------------------------------------------------------------
     * Função para ARMAZENAR os dados de um CLiente.                                                    *
     *                                                                                                  -
     * --------------------------------------------------------------------------------------------------
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password'          => ['required', 'string', Password::min(8), 'confirmed'],
            'telefone'          => ['nullable', 'string', 'max:20'],
            'data_nascimento'   => ['nullable', 'date', 'before_or_equal:today'],
            'sexo'              => ['nullable', 'string', Rule::in(['masculino', 'feminino', 'outro'])],
            'peso'              => ['nullable', 'numeric', 'min:0'],
            'altura'            => ['nullable', 'numeric', 'min:0'],
            'condicoes_medicas' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($validatedData) {

                $cliente = User::create([
                    'name'         => $validatedData['name'],
                    'email'        => $validatedData['email'],
                    'password'     => Hash::make($validatedData['password']),
                    'tipo_usuario' => 'cliente',
                    'status'       => 'ativo',
                ]);


                $cliente->clienteInformacoes()->create([
                    'telefone'          => $validatedData['telefone'] ?? null,
                    'data_nascimento'   => $validatedData['data_nascimento'] ?? null,
                    'sexo'              => $validatedData['sexo'] ?? null,
                    'peso'              => $validatedData['peso'] ?? null,
                    'altura'            => $validatedData['altura'] ?? null,
                    'condicoes_medicas' => $validatedData['condicoes_medicas'] ?? null,
                ]);
            });
        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao cadastrar o cliente. Por favor, tente novamente.');
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }



    /**-------------------------------------------------------------------------------------------------
     * Função para EXIBIR os dados de um CLiente.                                                       *
     * Busca os dados do ID de um cliente especifico junto com todos os dados do                        *
     * seu relaciomamento com a tabela cliente_informações.                                             *
     * Aborta a exibição caso não sej aum cliente.                                                      *
     * Retorna a view show com todos os dados do cliente.                                               *
     * --------------------------------------------------------------------------------------------------
     */
    public function show(int $id)
    {
        $cliente = User::with('clienteInformacoes')->findOrFail($id);

        abort_if($cliente->tipo_usuario !== 'cliente', 404);

        return view('clientes.show', [
            'cliente' => $cliente
        ]);
    }


    /**-------------------------------------------------------------------------------------------------
     * Função para EDITAR os dados de um CLiente.                                                      *
     * Recebe um ID como Parametro.                                                                    *
     * mesma logica do show.                                                                           *
     * Retorna a view clientes com o metodo edit.                                                                        
     * -------------------------------------------------------------------------------------------------
     */
    public function edit(int $id)
    {
        $cliente = User::findOrFail($id);

        abort_if($cliente->tipo_usuario !== 'cliente', 404);

        return view('clientes.edit', [
            'cliente' => $cliente
        ]);
    }


    /**-------------------------------------------------------------------------------------------------
     * Função para ATUALIZAR os dados de um CLiente.                                                    *
     * Recebe uma requisição e um  ID como Parametro.                                                   *
     * Busca o usuario pelo id, e verifica se este e um cliente.                                        *
     * Valida os dados do formulario.                                                                   *
     * Atualiza somete os campos especificos da tabela user.                                            *
     * E  a tabela cliente_informações Atualiza ou cria os dados adicionais do cliente .                *
     * REdireciona para rota index do clietes.                                                          *
     * ---------------------------------------------------------------------------------------------------
     */
    public function update(Request $request, int $id)
    {
        $cliente = User::findOrFail($id);

        abort_if($cliente->tipo_usuario !== 'cliente', 404);

        $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($cliente->id),],
            'status'            => ['required', 'string', Rule::in(['ativo', 'inativo'])],
            'telefone'          => ['nullable', 'string', 'max:20'],
            'data_nascimento'   => ['nullable', 'date'],
            'sexo'              => ['nullable', 'string', Rule::in(['masculino', 'feminino', 'outro'])],
            'peso'              => ['nullable', 'numeric', 'min:0'],
            'altura'            => ['nullable', 'numeric', 'min:0'],
            'condicoes_medicas' => ['nullable', 'string'],
        ]);

        $cliente->update($request->only('name', 'email', 'status'));

        $cliente->clienteInformacoes()->updateOrCreate(
            ['user_id' => $cliente->id],
            $request->only([
                'telefone',
                'data_nascimento',
                'sexo',
                'peso',
                'altura',
                'condicoes_medicas'
            ])
        );

        return redirect()->route('clientes.index')->with('success', 'Dados do cliente atualizados com sucesso!');
    }


    /**------------------------------------------------------------------------------------------------------
     * Função para EXCLUIR os dados de um CLiente.                                                          *
     * Recebe um  ID como Parametro.                                                                        *
     * Busca o usuario pelo id, e verifica se este e um cliente.                                            *
     * DELETA os DADOS DO CLIENTE da tabela user, e da tabela cliente_informações (cascade).                *
     * Redireciona para rota index do clietes.                                                              *
     * ------------------------------------------------------------------------------------------------------
     */
    public function destroy(int $id)
    {
        $cliente = User::findOrFail($id);

        abort_if($cliente->tipo_usuario !== 'cliente', 404);

        if ($cliente->fichasComoCliente()->exists()) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'Não é possível excluir este cliente porque ele possui treinos atribuídos. Remova os treinos primeiro.');
        }

        DB::beginTransaction();
        try {
            // Deleta as informações adicionais do cliente
            $cliente->clienteInformacoes()->delete();

            // Deleta o cliente
            $cliente->delete();

            DB::commit();

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao excluir cliente', [
                'message' => $e->getMessage(),
                'cliente_id' => $id,
            ]);

            return redirect()
                ->route('clientes.index')
                ->with('error', 'Ocorreu um erro ao remover o cliente. Tente novamente.');
        }
    }
}
