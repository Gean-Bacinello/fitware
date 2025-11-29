<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutheticationController extends Controller
{
    /**
     * Mostra o formulário de login
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Realiza login com os dados enviados
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */

    public function logar(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tenta fazer o login
        if (Auth::attempt($credentials, $request->boolean('remember'))) { // Adiciona "Lembrar-me"
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->tipo_usuario === 'gestor') {
                return redirect()->intended(route('dashboard.index'));
                
            } elseif ($user->tipo_usuario === 'instrutor') {
              
                return redirect()->intended(route('clientes.index'));

            } elseif ($user->tipo_usuario === 'cliente') {
                
                return redirect()->intended(route('cliente.meu-treino'));
            }

            return redirect('/');
        }

        // Se a autenticação falhar
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Realiza logout do usuário
     *
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
