<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bate-papo - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .bate-papo-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .content-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>
  <?php include 'includes-UsuarioLogado/navbar-UsuarioLogado.php'; ?>

  <main class="container mt-5 pt-4 bate-papo-container">
    <div class="gradient-card p-4 content-wrapper">
      <h1 class="mb-4">BATE-PAPO</h1>
      <p class="lead mb-4">Escolha um profissional para conversar</p>

      <div class="row">
        <!-- Profissional 1 -->
        <div class="col-md-6 mb-4">
          <div class="gradient-card p-3 h-100">
            <div class="d-flex align-items-center mb-3">
              <img src="https://via.placeholder.com/80" class="rounded-circle me-3" alt="Gabriel da Vila">
              <div>
                <h3 class="mb-0">GABRIEL DA VILA</h3>
                <span class="badge bg-primary">Nutricionista</span>
              </div>
            </div>
            <p class="mb-3">Especialista em nutrição esportiva com 10 anos de experiência.</p>
            <a href="conversa.php?prof=gabriel" class="btn btn-primary w-100">
              <i class="bi bi-chat-left-text"></i> Conversar
            </a>
          </div>
        </div>
        
        <!-- Profissional 2 -->
        <div class="col-md-6 mb-4">
          <div class="gradient-card p-3 h-100">
            <div class="d-flex align-items-center mb-3">
              <img src="https://via.placeholder.com/80" class="rounded-circle me-3" alt="Dr. Carlos">
              <div>
                <h3 class="mb-0">DR. CARLOS</h3>
                <span class="badge bg-success">Psicólogo</span>
              </div>
            </div>
            <p class="mb-3">Especialista em terapia cognitivo-comportamental.</p>
            <a href="conversa.php?prof=carlos" class="btn btn-primary w-100">
              <i class="bi bi-chat-left-text"></i> Conversar
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>
  
  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>