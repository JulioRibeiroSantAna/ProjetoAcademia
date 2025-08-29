<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['tipo_usuario'] = 'admin';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento - MEF (Adm)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .agendamento-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .form-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container agendamento-container">
    <?php include '../includes-Gerais/agendamento-dinamico.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>