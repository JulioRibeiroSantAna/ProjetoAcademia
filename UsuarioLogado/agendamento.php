<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['tipo_usuario'] = 'usuario';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento - MEF</title>
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
    /* Estilos específicos para agendamento */
    .agendamento-form {
      max-width: 800px;
      margin: 40px auto;
      padding: 40px;
    }
    .form-control, .form-select {
      padding: 1rem 1.5rem !important;
      font-size: 1.1rem !important;
      border-radius: 12px !important;
    }
    .form-label {
      font-size: 1.1rem !important;
      font-weight: 600 !important;
      margin-bottom: 0.75rem !important;
    }
    .btn-save {
      padding: 1.25rem 2.5rem !important;
      font-size: 1.2rem !important;
    }
    h3 {
      font-size: 1.5rem !important;
      padding-bottom: 1rem !important;
      border-bottom: 2px solid rgba(255,255,255,0.2) !important;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container agendamento-container">
    <?php include '../includes-Gerais/agendamento-dinamico.php'; ?>
  </main>

  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php include '../includes-Gerais/footer.php'; ?>
</body>
</html>