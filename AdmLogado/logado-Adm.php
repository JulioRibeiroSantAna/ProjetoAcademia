<?php require_once '../config.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MEF - Área Logada Adm</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>
  <!-- Conteúdo Principal -->
  <main>
    <!-- Seção Hero -->
    <?php include '../includes-Gerais/hero-section.php'; ?>

    <!-- Sobre -->
    <?php include_once '../includes-Gerais/sobre.php';?>

    <!-- Profissionais -->
    <?php include '../includes-Gerais/profissionais-section.php';?>

  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="../js/main.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>
