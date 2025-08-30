<?php
// includes-Gerais/conversa-dinamica.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Dados da conversa (exemplo)
$nomeProfissional = "GABRIEL DA VILA";
$especialidadeProfissional = "Nutricionista Esportivo";
?>

<div class="mef-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">BATE-PAPO</h1>
            <p class="lead mb-0">Conversando com <span id="professionalName"><?php echo $nomeProfissional; ?></span></p>
        </div>
        <div>
            <a href="<?php echo $is_admin ? '../AdmLogado/bate-papo-Adm.php' : '../UsuarioLogado/bate-papo.php'; ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="mef-card p-0">
        <div class="card-header d-flex align-items-center p-3">
            <img src="../imagens-teste/goku.jpg" class="rounded-circle me-3" width="50" height="50" alt="Profissional">
            <div>
                <h5 class="mb-0" id="nomeProfissional"><?php echo $nomeProfissional; ?></h5>
                <small class="text-muted" id="especialidadeProfissional"><?php echo $especialidadeProfissional; ?></small>
            </div>
        </div>
        
        <div class="chat-container">
            <div class="chat-messages p-3" id="chatMessages">
                <div class="message-content bg-primary text-white mb-3" style="max-width: 70%; margin-right: auto;">
                    Olá! Como posso ajudá-lo hoje?
                </div>
                <div class="message-content bg-secondary text-white mb-3" style="max-width: 70%; margin-left: auto;">
                    Gostaria de agendar uma consulta sobre nutrição esportiva.
                </div>
                <div class="message-content bg-primary text-white mb-3" style="max-width: 70%; margin-right: auto;">
                    Claro! Temos horários disponíveis na próxima semana. Qual dia prefere?
                </div>
            </div>
            
            <div class="chat-input p-3 border-top">
                <div class="input-group">
                    <input type="text" 
                           class="form-control mef-form-control" 
                           placeholder="Digite sua mensagem..." 
                           id="messageInput">
                    <button class="btn mef-btn-primary" type="button" id="sendButton">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.getElementById('chatMessages');
    
    function sendMessage() {
        const message = messageInput.value.trim();
        if (message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'message-content bg-secondary text-white mb-3';
            messageElement.style.maxWidth = '70%';
            messageElement.style.marginLeft = 'auto';
            messageElement.textContent = message;
            
            chatMessages.appendChild(messageElement);
            messageInput.value = '';
            
            // Scroll para o final
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Simular resposta após 1 segundo
            setTimeout(() => {
                const responseElement = document.createElement('div');
                responseElement.className = 'message-content bg-primary text-white mb-3';
                responseElement.style.maxWidth = '70%';
                responseElement.style.marginRight = 'auto';
                responseElement.textContent = 'Entendi. Vou verificar a disponibilidade para você.';
                
                chatMessages.appendChild(responseElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);
        }
    }
    
    sendButton.addEventListener('click', sendMessage);
    
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script>