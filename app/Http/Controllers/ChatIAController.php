<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatIAController extends Controller
{
    private function getGeminiApiKey()
    {
        // Tenta múltiplas formas de obter a API key
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
        
            $envFile = base_path('.env');
            if (file_exists($envFile)) {
                $envContent = file_get_contents($envFile);
                if (preg_match('/^GEMINI_API_KEY=(.*)$/m', $envContent, $matches)) {
                    $apiKey = trim($matches[1]);
                }
            }
        }

        if (empty($apiKey)) {
            $apiKey = $_ENV['GEMINI_API_KEY'] ?? null;
        }

        if (empty($apiKey)) {
            $apiKey = getenv('GEMINI_API_KEY') ?: null;
        }

        return $apiKey;
    }

    public function responder(Request $request)
    {
        $pergunta = $request->input('pergunta');

        if (empty($pergunta)) {
            return response()->json([
                'resposta' => 'Por favor, digite uma pergunta.'
            ], 400);
        }

        try {
            $apiKey = $this->getGeminiApiKey();

            Log::info('=== USANDO MODELOS 2.5 (SETEMBRO 2025) ===');

            if (empty($apiKey)) {
                return response()->json([
                    'resposta' => 'Erro: GEMINI_API_KEY não encontrada.'
                ], 500);
            }

            $modelosAtuais = [
                'gemini-2.5-flash-lite',  
                'gemini-2.5-flash',       
                'gemini-2.5-pro',         
            ];

            $response = null;
            $modeloUsado = null;

            // Tenta cada modelo atual até um funcionar
            foreach ($modelosAtuais as $modelo) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent?key=" . $apiKey;

                Log::info("Tentando modelo atual: {$modelo}");

                try {
                    $response = Http::timeout(30)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "Você é um personal trainer virtual especializado **exclusivamente** em treinos e exercícios físicos. 
                                                    Responda sempre em português de forma clara, organizada, objetiva e motivadora.  

                                                    ⚠️ Regras:  
                                                    - Responda SOMENTE sobre treinos e exercícios.  
                                                    - Se a pergunta não for sobre treinos ou exercícios, responda educadamente:  
                                                    'Desculpe, só consigo responder perguntas relacionados a treinos e exercícios físicos.'  

                                                    📋 Estrutura obrigatória da resposta:  
                                                    1. Nome do treino (em uma linha separada).  
                                                    2. Lista de exercícios (um por linha) no formato:  
                                                    séries x repetições - Nome do exercício  
                                                    3. Uma breve descrição geral do treino (até 2 frases).  
                                                    4. Uma lista com uma breve descrição de cada exercício (1 frase curta por exercício).  

                                                    Pergunta: " . $pergunta

                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 1024,
                        ]
                    ]);

                    Log::info("Status HTTP para {$modelo}: " . $response->status());

                    if ($response->successful()) {
                        $modeloUsado = $modelo;
                        break;
                    } else {
                        $errorData = $response->json();
                        Log::warning("Modelo {$modelo} falhou: " . json_encode($errorData));
                        $response = null;
                    }
                } catch (\Exception $e) {
                    Log::warning("Erro ao tentar modelo {$modelo}: " . $e->getMessage());
                    $response = null;
                }
            }

            if (!$response) {
                Log::error('Nenhum modelo 2.5 funcionou');
                return response()->json([
                    'resposta' => 'Desculpe, os modelos de IA estão temporariamente indisponíveis. Os modelos 1.5 foram aposentados e estamos migrando para os novos modelos 2.5. Tente novamente em alguns minutos.'
                ], 500);
            }

            Log::info("Modelo {$modeloUsado} funcionou!");

            $dados = $response->json();
            Log::info('Estrutura da resposta:', $dados);

            // Verifica a estrutura da resposta
            if (isset($dados['candidates'][0]['content']['parts'][0]['text'])) {
                $resposta = trim($dados['candidates'][0]['content']['parts'][0]['text']);
                return response()->json(['resposta' => $resposta]);
            } else {
                Log::warning('Estrutura inesperada na resposta:', $dados);

                // Verifica se há alguma mensagem de erro específica
                if (isset($dados['error'])) {
                    $errorMsg = $dados['error']['message'] ?? 'Erro desconhecido na API';
                    Log::error('Erro da API: ' . $errorMsg);
                    return response()->json([
                        'resposta' => "Erro na API: {$errorMsg}"
                    ], 500);
                }

                return response()->json([
                    'resposta' => 'Resposta em formato inesperado. Os modelos podem estar em atualização.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro no ChatIA: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'resposta' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }
}
