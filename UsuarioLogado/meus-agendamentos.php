<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado como usuário comum
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'usuario') {
    header('Location: ../Autenticacao/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meus Agendamentos - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .agendamentos-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .table-container {
      flex: 1;
      margin-bottom: 2rem;
    }
    .table-hover tbody tr:hover {
      background-color: rgba(93, 156, 236, 0.1);
    }
    .btn-group .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-4 agendamentos-container">
    <?php include '../includes-Gerais/meus-agendamentos-dinamico.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>