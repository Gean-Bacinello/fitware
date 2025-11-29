<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
     /**
     * mostrar o formulário de cadastro de usuários
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Realiza a criação do usuário no banco de dados
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password'    => ['required', 'string', Password::min(8), 'confirmed'], 
       
        ]);

        $dados = $request->only(['name', 'email', 'password']);
        $dados['password'] = Hash::make($dados['password']);
        $dados['tipo_usuario'] =  'gestor';
        $dados['status'] =  'ativo';
             
        User::create($dados);

        return redirect()->route('login');
    }
}
