<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do Usuário - MEF (Admin)</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="logado-Adm.php">MEF</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#fale-conosco">Fale Conosco</a>
          </li>
        </ul>

        <div class="dropdown ms-3">
          <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> Menu
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="videos-apoio-Adm.php">Vídeos de Apoio</a></li>
            <li class="dropdown-submenu">
              <a class="dropdown-item d-flex justify-content-between align-items-center submenu-toggle" 
                 href="#" 
                 role="button">
                Profissionais <i class="bi bi-chevron-down small"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="agendamento-Adm.php">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="bate-papo-Adm.php">Bate-Papo</a></li>
                <li><a class="dropdown-item" href="meus-agendamentos-Adm.php">Meus Agendamentos</a></li>
              </ul>
            </li>
            <li><a class="dropdown-item" href="perfil-Adm.php">Perfil de Usuário</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../index.php">Sair</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo principal -->
  <main class="container mt-5 pt-5">
    <div class="gradient-card mx-auto p-4" style="max-width: 500px;">
      <div class="text-center mb-4">
        <div class="profile-photo mx-auto"
          style="background-image: url('https://cdn-icons-png.flaticon.com/512/3135/3135715.png'); width: 150px; height: 150px;"
          aria-label="Foto do usuário Julio Ribeiro"
          role="img"
        ></div>
      </div>

      <h2 class="text-center mb-4">Perfil do Usuário</h2>

      <div class="profile-info">
        <div class="mb-3">
          <label class="form-label fw-bold">Nome:</label>
          <div class="form-control bg-dark text-white">Julio Ribeiro</div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Email:</label>
          <div class="form-control bg-dark text-white">julioribeiro041@gmail.com</div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Telefone:</label>
          <div class="form-control bg-dark text-white">(51) 99999-9999</div>
        </div>
      </div>

      <a href="editar-perfil-Adm.php" class="btn btn-primary w-100 mt-3">Editar Perfil</a>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>