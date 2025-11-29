<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ContaController extends Controller
{
    /**--------------------------------------------------------
     * Exibe os dados da conta do usuário autenticado.        *
     * --------------------------------------------------------
     */
    public function show()
    {
        $usuario = Auth::user();
        /** @var \App\Models\User $usuario */ 
        $usuario->load(['clienteInformacoes', 'instrutorInformacoes']); 

        return view('conta.show', [
            'usuario' => $usuario
        ]);
    }

    /**---------------------------------------------------------------
     * Retorna a view para edição dos dados da conta do usuário.     *
     * ---------------------------------------------------------------
     */
    public function edit()
    {
        $usuario = Auth::user();
        /** @var \App\Models\User $usuario */ 
        $usuario->load(['clienteInformacoes', 'instrutorInformacoes']); 

        return view('conta.edit', [
            'usuario' => $usuario
        ]);
    }


    /** ------------------------------------------------------------------------------------------------
     * ATUALIZA os dados do perfil do usuário logado (Cliente ou Instrutor).                           *                                                                           *
     * Captura o usuário autenticado via Auth.                                                         *
     * Valida entradas: Dados Pessoais, Senha (opcional), Imagem e Campos Específicos.                 *
     * Inicia Transação de Banco de Dados (DB Transaction) para integridade.                           *
     * Gerencia upload de imagem: remove a antiga do Storage e salva a nova.                           *
     * Atualiza registro principal do usuário (nome, email, senha, foto).                              *
     * Atualiza ou Cria dados nas tabelas relacionadas (clientes/instrutores).                         *
     * Redireciona para a visualização da conta com mensagem de sucesso.                               *
     * -------------------------------------------------------------------------------------------------
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $usuario */ 
        $usuario = Auth::user();

        $validatedData = $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'password'          => ['nullable', 'confirmed', Password::min(8)],
            'imagem_perfil_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:4096'], // alteração para upload de imagem
            // Campos do relacionamento clienteInformacoes 
            'telefone'          => ['nullable', 'string', 'max:20'],
            'data_nascimento'   => ['nullable', 'date'],
            'sexo'              => ['nullable', 'string', Rule::in(['masculino', 'feminino', 'outro'])],
            'peso'              => ['nullable', 'numeric', 'min:0'],
            'altura'            => ['nullable', 'numeric', 'min:0'],
            'condicoes_medicas' => ['nullable', 'string'],
            // Campos do relacionamento instrutorInformacoes 
            'CREF'              => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($usuario, $validatedData, $request) {
            // Atualiza dados básicos do usuário
            $dadosUser = [
                'name'  => $validatedData['name'],
                'email' => $validatedData['email'],
            ];

            if (!empty($validatedData['password'])) {
                $dadosUser['password'] = Hash::make($validatedData['password']);
            }

            // Tratar upload da imagem de perfil
            if ($request->hasFile('imagem_perfil_url')) {
                // Apaga imagem antiga, se existir
                if ($usuario->imagem_perfil_url) {
                    Storage::disk('public')->delete($usuario->imagem_perfil_url);
                }

                // Armazena nova imagem
                $path = $request->file('imagem_perfil_url')->store('perfil_imagens', 'public');
                $dadosUser['imagem_perfil_url'] = $path;
            } else {
                // Mantém valor atual se não enviou imagem
                $dadosUser['imagem_perfil_url'] = $usuario->imagem_perfil_url;
            }

            $usuario->update($dadosUser); 

            // Atualizar clienteInformacoes se usuário for cliente
            if ($usuario->tipo_usuario === 'cliente') {
                $usuario->clienteInformacoes()->updateOrCreate(
                    ['user_id' => $usuario->id],
                    [
                        'telefone'          => $validatedData['telefone'] ?? null,
                        'data_nascimento'   => $validatedData['data_nascimento'] ?? null,
                        'sexo'              => $validatedData['sexo'] ?? null,
                        'peso'              => $validatedData['peso'] ?? null,
                        'altura'            => $validatedData['altura'] ?? null,
                        'condicoes_medicas' => $validatedData['condicoes_medicas'] ?? null,
                    ]
                );
            }

            // Atualizar instrutorInformacoes se usuário for instrutor
            if ($usuario->tipo_usuario === 'instrutor') {
                $usuario->instrutorInformacoes()->updateOrCreate(
                    ['user_id' => $usuario->id],
                    [
                        'CREF'    => $validatedData['CREF'] ?? null,
                        'telefone' => $validatedData['telefone'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('conta.show')->with('success', 'Conta atualizada com sucesso!');
    }


    /** NÃO IMPLEMENTADO!
     * Exclui a conta do usuário autenticado. 
     */
    //public function destroy()
    //{
        /** @var \App\Models\User $usuario */ 
       // $usuario = Auth::user();

        // Faz logout antes de deletar
        //Auth::logout();

        //DB::transaction(function () use ($usuario) {
            // Deleta dados relacionados conforme tipo usuário
           // if ($usuario->tipo_usuario === 'cliente') {
            //    $usuario->clienteInformacoes()->delete(); // 'clienteInformacoes' e 'delete' reconhecidos
            //}

           // if ($usuario->tipo_usuario === 'instrutor') {
           //     $usuario->instrutorInformacoes()->delete(); // 'instrutorInformacoes' e 'delete' reconhecidos
           // }

            // Deleta usuário
            //$usuario->delete(); // 'delete' é reconhecido
      //  });

       // return redirect('/')->with('success', 'Sua conta foi excluída com sucesso.');
    //}
}
