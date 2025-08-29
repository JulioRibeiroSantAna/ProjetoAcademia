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
  <title>Bate-papo - Admin - MEF</title>
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
    .gradient-card {
      background: linear-gradient(135deg, rgba(26, 26, 46, 0.8), rgba(26, 26, 46, 0.6));
      backdrop-filter: blur(10px);
      border-radius: 15px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: white;
    }
    .professional-card {
      transition: transform 0.3s;
      margin-bottom: 20px;
    }
    .professional-card:hover {
      transform: translateY(-5px);
    }
    .photo-preview {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255, 255, 255, 0.2);
      margin-bottom: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .schedule-day {
      background: rgba(0, 0, 0, 0.2);
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .time-input-group {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 5px;
    }
    .add-time-btn {
      margin-top: 5px;
    }
    .remove-time-btn {
      color: #ff6b6b;
      cursor: pointer;
    }
    .day-checkbox {
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-4 bate-papo-container">
    <?php include '../includes-Gerais/bate-papo-dinamico.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>