<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class InstrutorController extends Controller
{

    /** ------------------------------------------------------------------------------------------------
     * LISTA os usuários do Tipo INSTRUTOR, gerenciando filtros e paginação.                           *                                                                           *
     * Inicia a construção da query no model User.                                                     *
     * Aplica o filtro base obrigatório: apenas usuários do tipo 'instrutor'.                          *
     * Verifica e aplica filtro de BUSCA (parcial por Nome/Email ou exata por ID).                     *
     * Ordena pelos registros mais recentes e pagina os resultados (5 por página).                     *
     * Retorna a View 'instrutores.index' com os dados processados.                                    *
     * -------------------------------------------------------------------------------------------------
     */
    public function index(Request $request)
    {
        $query = User::query();

        $query->where('tipo_usuario', 'instrutor');

        if ($request->filled('search')) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%') 
                    ->orWhere('id', '=', $searchTerm); 
            });
        }

        $instrutores = $query->latest()->paginate(5);

        return view('instrutores.index', [
            'instrutores' => $instrutores
        ]);
    }

    /** ------------------------------------------------------------------------------------------------
     * Mostra a view de cadastro de instrutores                                                        *
     *                                                                                                 *                   
     * -------------------------------------------------------------------------------------------------
     */
    public function create()
    {
        return view('instrutores.create');
    }


    /** ------------------------------------------------------------------------------------------------
     * Função para ARMAZENAR os dados de um Instrutor.                                                 *
     * Valida os dados do formulario.                                                                  *
     * Inicia uma transaction para garantir que os dados sejam inseridos em ambas ->                   *
     * as tabelas(user, InstrutorInformacoes).                                                         *
     * Caso ocorra uma exception sera retornado o input do erro e redirecionado uma tela de volta.     *
     * Caso a inserção seja realizada com sucesso retorna para pagina inicial do                       *
     * instrutor com a menssagem                                                                       *
     * -------------------------------------------------------------------------------------------------
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password'          => ['required', 'string', Password::min(8), 'confirmed'],
            'telefone'          => ['nullable', 'string', 'max:20'],
            'CREF'              => ['nullable', 'numeric', 'digits_between:1,6'],
        ]);

        try {
            DB::transaction(function () use ($validatedData) {

                $instrutor = User::create([
                    'name'         => $validatedData['name'],
                    'email'        => $validatedData['email'],
                    'password'     => Hash::make($validatedData['password']),
                    'tipo_usuario' => 'instrutor',
                    'status'       => 'ativo',
                ]);

                $instrutor->InstrutorInformacoes()->create([

                    'telefone'  => $validatedData['telefone'] ?? null,
                    'CREF'      => $validatedData['CREF'] ?? null,
                ]);
            });
        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao cadastrar o Instrutor. Por favor, tente novamente.');
        }

        return redirect()->route('instrutores.index')->with('success', 'Instrutor cadastrado com sucesso!');
    }


    /**-------------------------------------------------------------------------------------------------
     * Função para EXIBIR os dados de um Instrutor.                                                    *
     * Busca os dados do ID de um Instrutor especifico junto com todos os dados do                     *
     * seu relaciomamento com a tabela InstrutorInformacoes.                                           *
     * Aborta a exibição caso não seja um cliente.                                                     *
     * Retorna a view show com todos os dados do cliente.                                              *
     * -------------------------------------------------------------------------------------------------
     */
    public function show(string $id)
    {
        $instrutor = User::with('InstrutorInformacoes')->findOrFail($id);

        abort_if($instrutor->tipo_usuario !== 'instrutor', 404);

        return view('instrutores.show', [
            'instrutor' => $instrutor
        ]);
    }


    /**-------------------------------------------------------------------------------------------------
     * Função para EDITAR os dados de um Instrutor.                                                    *
     * Recebe um ID como Parametro.                                                                    *
     * mesma logica do show.                                                                           *
     * -------------------------------------------------------------------------------------------------
     */
    public function edit(string $id)
    {
        $instrutor = User::findOrFail($id);

        abort_if($instrutor->tipo_usuario !== 'instrutor', 404);

        return view('instrutores.edit', [
            'instrutor' => $instrutor
        ]);
    }


    /**-------------------------------------------------------------------------------------------------
     * Função para ATUALIZAR os dados de um Instrutor.                                                  *
     * Recebe uma requisição e um ID como parâmetro.                                                    *
     * 1. Encontra o instrutor pelo ID ou falha com um erro 404.                                        *
     * 2. Garante que o usuário encontrado é de fato um instrutor.                                      *
     * 3. Valida a requisição.                                                                          *
     * 4. Usa uma transação para garantir que ambas as atualizações funcionem ou ambas falhem.          *
     * 5. Prepara os dados para a tabela 'users'.                                                       *
     * 6. Verifica se uma nova senha foi enviada.                                                       *
     * 7. Atualiza o registro do usuário (instrutor).                                                   *
     * 8. Atualiza ou cria as informações relacionadas na tabela 'instrutor_informacoes'.               *
     * 9. Se tudo ocorrer bem, redireciona com uma mensagem de sucesso.                                 *
     * --------------------------------------------------------------------------------------------------
     */
    public function update(Request $request, string $id)
    {

        $instrutor = User::findOrFail($id);

        abort_if($instrutor->tipo_usuario !== 'instrutor', 404);

        $validatedData = $request->validate([
            'name'     => ['required', 'string',  'max:150'],
            'email'    => ['required', 'string',  'email', 'max:255', Rule::unique('users')->ignore($instrutor->id)],
            'status'   => ['required', 'string',  Rule::in(['ativo', 'inativo'])],
            'password' => ['nullable', 'string',  Password::min(8), 'confirmed'], // Senha é opcional na atualização
            'telefone' => ['nullable', 'string',  'max:20'],
            'CREF'     => ['nullable', 'numeric', 'digits_between:1,6'],
        ]);

        try {

            DB::transaction(function () use ($instrutor, $validatedData) {


                $userData = [
                    'name'   => $validatedData['name'],
                    'email'  => $validatedData['email'],
                    'status' => $validatedData['status'],
                ];

                if (!empty($validatedData['password'])) {
                    $userData['password'] = Hash::make($validatedData['password']);
                }

                $instrutor->update($userData);

                $instrutor->InstrutorInformacoes()->updateOrCreate(
                    ['user_id' => $instrutor->id],
                    [
                        'telefone' => $validatedData['telefone'] ?? null,
                        'CREF'     => $validatedData['CREF'] ?? null,
                    ]
                );
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocorreu um erro ao atualizar os dados do Instrutor. Por favor, tente novamente.');
        }

        return redirect()->route('instrutores.index')->with('success', 'Instrutor atualizado com sucesso!');
    }


    /**------------------------------------------------------------------------------------------------------
     * Função para EXCLUIR os dados de um Instrutor.                                                        *
     * Recebe um  ID como Parametro.                                                                        *
     * Busca o usuario pelo id, e verifica se este e um Instrutor.                                          *
     * DELETA os DADOS DO INSTRUTOR da tabela user, e da InstrutorInformacoes (cascade).                    *
     * Redireciona para rota index do INSTRUTORES.                                                          *
     * ------------------------------------------------------------------------------------------------------
     */
    public function destroy(string $id)
    {
        $instrutor = User::findOrFail($id);

        abort_if($instrutor->tipo_usuario !== 'instrutor', 404);

        $instrutor->InstrutorInformacoes()->delete();
        $instrutor->delete();

        return redirect()->route('instrutores.index')->with('success', 'instrutor removido com sucesso!');
    }
}
