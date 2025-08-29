<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário é admin de verdade
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: ../Autenticacao/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bate-papo - Conversa (Admin) - MEF</title>
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
    <?php include '../includes-Gerais/conversa-dinamica.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/menu.js"></script>
  <script type="module" src="../js/main.js"></script>
</body>
</html>