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
  <?php include 'includes-Adm/navbar-Adm.php'; ?>
  <!-- Conteúdo Principal -->
  <main>
    <!-- Seção Hero -->
    <section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">Bem-vindo(a) ao MEF</h1>
        <h2 class="h3 mb-5">Área do Usuário Adm</h2>
        <div class="d-flex justify-content-center gap-3">
          <a href="agendamento-Adm.php" class="btn btn-primary btn-lg">Agendar Consulta</a>
          <a href="bate-papo-Adm.php" class="btn btn-outline-light btn-lg">Bate-Papo</a>
        </div>
      </div>
    </section>

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
