<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;

class RedirecionaSeAutenticado
{
    /**
     * Lida com uma requisição de entrada.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                $user = Auth::user();

                // Se for admin mande para o /dashboard
                if ($user->tipo_usuario == 'gestor') {
                    return redirect('/dashboard');
                }

                // Se for instrutor, mande para a página de clientes
                if ($user->tipo_usuario == 'instrutor') {
                    return redirect('/clientes'); 
                }

                // Se for cliente, mande para a página de treinos
                if ($user->tipo_usuario == 'cliente') {
                    return redirect('/'); 
                }

                // Fallback
                return redirect('/dashboard');
            }
        }

        // Se não estiver logado, continua para a página de login
        return $next($request);
    }
}