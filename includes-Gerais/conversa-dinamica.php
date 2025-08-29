<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin (usando o valor REAL da sessão)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Dados da conversa (exemplo)
$nomeProfissional = "GABRIEL DA VILA";
$especialidadeProfissional = "Nutricionista Esportivo";
?>

<div class="gradient-card p-4 chat-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">BATE-PAPO</h1>
            <p class="lead mb-0">Conversando com <span id="professionalName"><?php echo $nomeProfissional; ?></span></p>
        </div>
        <div>
            <a href="<?php echo $is_admin ? 'bate-papo-Adm.php' : 'bate-papo.php'; ?>" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="gradient-card p-0">
        <div class="card-header d-flex align-items-center">
            <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Profissional">
            <div>
                <h5 class="mb-0" id="nomeProfissional"><?php echo $nomeProfissional; ?></h5>
                <small class="text-muted" id="especialidadeProfissional"><?php echo $especialidadeProfissional; ?></small>
            </div>
        </div>
        
        <div class="chat-container">
            <div class="chat-messages p-3" id="chatMessages">
                <!-- Mensagens serão inseridas aqui via JavaScript -->
            </div>
            
            <div class="chat-input p-3">
                <div class="input-group">
                    <input type="text" 
                           class="form-control" 
                           placeholder="Digite sua mensagem..." 
                           id="messageInput">
                    <button class="btn btn-primary" type="button" id="sendButton">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../js/chat.js" type="module"></script>