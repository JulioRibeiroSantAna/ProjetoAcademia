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
  <title>Perfil do Usuário - MEF (Admin)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .perfil-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .profile-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    /* Estilos específicos para perfil */
    .profile-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 40px;
    }
    .profile-info .form-control {
      padding: 1rem 1.5rem !important;
      font-size: 1.1rem !important;
      background-color: rgba(255, 255, 255, 0.1) !important;
    }
    .form-label {
      font-size: 1.1rem !important;
      font-weight: 600 !important;
    }
    .btn-lg {
      padding: 1rem 2rem !important;
      font-size: 1.2rem !important;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-5 perfil-container">
    <?php include '../includes-Gerais/perfil-dinamico.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>