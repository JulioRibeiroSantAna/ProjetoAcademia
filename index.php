<?php require_once 'config.php';?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEF</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#home">MEF</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#footer">Fale Conosco</a>
          </li>
        </ul>

        <a href="Autenticacao/login.php" class="btn btn-light">
          <i class="bi bi-person-circle me-1"></i> Entrar
        </a>
      </div>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <main>
    <!-- Seção Hero -->
    <section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">PREPARE-SE PARA MUDAR</h1>
        <h2 class="h3 mb-5">PARA MELHOR.</h2>
        <div class="d-flex justify-content-center gap-3">
          <a href="Autenticacao/login.php" class="btn btn-primary btn-lg">Entrar na Plataforma</a>
          <a href="#sobre" class="btn btn-outline-light btn-lg">Saiba Mais</a>
        </div>
      </div>
    </section>

  <!-- Sobre -->
  <?php include 'includes-Gerais/sobre.php'; ?>

    <!-- Profissionais -->
    <?php include 'includes-Gerais/profissionais-section.php'; ?>

  </main>

  <?php include 'includes-Gerais/footer.php'; ?>  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="js/main.js"></script>
</body>
</html>