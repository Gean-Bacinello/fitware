<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarTipoUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  ...$tiposPermitidos
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$tiposPermitidos)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form');
        }

        $user = Auth::user();

        if (in_array($user->tipo_usuario, $tiposPermitidos)) {
            return $next($request); // Permite o acesso
        }

        // Se não estiver na lista, nega o acesso
        abort(403, 'Acesso Não Autorizado');
    }
}