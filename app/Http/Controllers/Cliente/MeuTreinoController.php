<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ficha; 

class MeuTreinoController extends Controller
{
    /**
     * Exibe a ficha de treino ativa (mais recente) do cliente logado.
     */
    public function index()
    {
        // Pega o ID do usuário (cliente) logado
        $clienteId = Auth::id();

        // Busca a ÚLTIMA ficha atribuída a este cliente
        // Carrega o 'treino' com seus 'exercicios' e o 'instrutor'
        $fichaAtiva = Ficha::where('cliente_id', $clienteId)
                            ->with(['treino.exercicios', 'instrutor'])
                            ->latest('data_atribuicao') // Pega a mais recente
                            ->first(); // Pega apenas uma

        // Envia a ficha (ou null, se não houver) para a view
        return view('cliente.meu-treino', compact('fichaAtiva'));
    }
}