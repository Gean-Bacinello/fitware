{{--
=====================================================================
ARQUIVO: resources/views/chat.blade.php
AUTOR: Gean Correa Bacinello
DATA DE CRIAÇÃO: --
ÚLTIMA MODIFICAÇÃO: 07/10/2025
VERSÃO: 1.0
=====================================================================
DESCRIÇÃO:
    arquivo referente ao chat que ultiliza a API do gemini.
=====================================================================
DEPENDÊNCIAS:
    - Banco de dados MySQL
    - Laravel 12.x
    - Bootstrap 5
    - CSS personalizado: --
    - Rota: chat-ia.view
    - Controller: ChatIAController.php
=====================================================================
--}}

@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-robot me-2"></i>
                    <h4 class="mb-0">Chat de IA - Personal Trainer Virtual</h4>
                </div>
                <div class="card-body">
                    <!-- Área do Chat -->
                    <div id="chatArea" class="border rounded p-3 mb-3" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                        <div class="text-muted text-center">
                            <i class="bi bi-chat-dots fs-1"></i>
                            <p class="mt-2">Olá! Sou seu personal trainer virtual. Como posso te ajudar com seus treinos hoje?</p>
                        </div>
                    </div>

                    <!-- Formulário -->
                    <form id="chatForm">
                        @csrf
                        <div class="input-group">
                            <textarea name="pergunta" id="pergunta" rows="2" class="form-control" 
                                placeholder="Digite sua dúvida sobre treinos, exercícios, nutrição..."></textarea>
                            <button type="submit" class="btn btn-primary" id="btnEnviar">
                                <i class="bi bi-send"></i> Enviar
                            </button>
                        </div>
                    </form>

                    <!-- Loading -->
                    <div id="loading" class="text-center mt-3" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <span class="ms-2">Pensando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message {
    margin-bottom: 15px;
}

.message.user {
    text-align: right;
}

.message.user .message-content {
    background-color: #84c441;
    color: white;
    display: inline-block;
    padding: 10px 15px;
    border-radius: 18px 18px 5px 18px;
    max-width: 70%;
    word-wrap: break-word;
}   

.message.ai {
    text-align: left;
}

.message.ai .message-content {
    background-color: #e9ecef;
    color: #333;
    display: inline-block;
    padding: 10px 15px;
    border-radius: 18px 18px 18px 5px;
    max-width: 70%;
    word-wrap: break-word;
}

.message-content {
  white-space: pre-wrap; /* preserva \n como quebra de linha */
}

.message .message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 5px;
}

#chatArea::-webkit-scrollbar {
    width: 6px;
}

#chatArea::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#chatArea::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#chatArea::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.btn-primary {
  background-color: #84c441;
  border-color: #84c441;
  color: #fff; 
}

.btn-primary:hover {
  background-color: #6da437; 
  border-color: #6da437;
}

</style>

<script>
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const perguntaInput = document.getElementById('pergunta');
    const pergunta = perguntaInput.value.trim();
    const chatArea = document.getElementById('chatArea');
    const loading = document.getElementById('loading');
    const btnEnviar = document.getElementById('btnEnviar');
    
    if (!pergunta) {
        alert('Por favor, digite uma pergunta.');
        return;
    }
    
    // Limpa a mensagem inicial se for a primeira pergunta
    if (chatArea.querySelector('.text-muted')) {
        chatArea.innerHTML = '';
    }
    
    // Adiciona a pergunta do usuário
    addMessage('user', pergunta);
    perguntaInput.value = '';
    
    // Mostra loading
    loading.style.display = 'block';
    btnEnviar.disabled = true;
    
    try {
        const response = await fetch("{{ route('chat-ia.responder') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ pergunta })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            addMessage('ai', data.resposta);
        } else {
            addMessage('ai', data.resposta || 'Erro ao processar sua pergunta.');
        }
        
    } catch (error) {
        console.error('Erro:', error);
        addMessage('ai', 'Erro de conexão. Verifique sua internet e tente novamente.');
    } finally {
        loading.style.display = 'none';
        btnEnviar.disabled = false;
        perguntaInput.focus();
    }
});

function addMessage(type, content) {
    const chatArea = document.getElementById('chatArea');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;

    const now = new Date();
    const timeString = now.toLocaleTimeString('pt-BR', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });

    // Substitui \n por <br>
    const safeContent = content.replace(/\n/g, "<br>");

    messageDiv.innerHTML = `
        <div class="message-content">${safeContent}</div>
        <div class="message-time">${timeString}</div>
    `;

    chatArea.appendChild(messageDiv);
    chatArea.scrollTop = chatArea.scrollHeight;
}

// Permite enviar com Enter (Ctrl+Enter para quebra de linha)
document.getElementById('pergunta').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.ctrlKey) {
        e.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
});

// Auto-resize do textarea
document.getElementById('pergunta').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});
</script>
@endsection