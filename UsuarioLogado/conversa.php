<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conversa - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .conversa-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .chat-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    .chat-messages {
      height: 400px;
      overflow-y: auto;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-4 conversa-container">
    <div class="gradient-card p-4 chat-wrapper">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="mb-0">BATE-PAPO</h1>
          <p class="lead mb-0">Conversando com <span id="professionalName">GABRIEL DA VILA</span></p>
        </div>
        <div>
          <a href="bate-papo.php" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>
        </div>
      </div>

      <div class="gradient-card p-0">
        <div class="card-header d-flex align-items-center">
          <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Profissional">
          <div>
            <h5 class="mb-0" id="nomeProfissional">GABRIEL DA VILA</h5>
            <small class="text-muted" id="especialidadeProfissional">Nutricionista Esportivo</small>
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
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/chat.js" type="module"></script>
  <script type="module" src="../js/main.js"></script>
</body>
</html>