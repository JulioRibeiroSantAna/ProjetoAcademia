<?php 
// UsuarioLogado/logado.php
require_once '../config.php';

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
  <title>Área do Usuário - MEF</title>
  <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main>
    <!-- Seção Hero -->
    <?php include '../includes-Gerais/hero-section.php'; ?>

    <!-- Sobre -->
    <?php include '../includes-Gerais/sobre.php'; ?>

    <!-- Profissionais -->
    <?php include '../includes-Gerais/profissionais-section.php'; ?>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>