<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vídeos de Apoio - MEF</title>
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
      <a class="navbar-brand" href="logado.php">MEF</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="logado.php#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado.php#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado.php#fale-conosco">Fale Conosco</a>
          </li>
        </ul>

        <div class="dropdown ms-3">
          <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> Menu
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="videos-apoio.php">Vídeos de Apoio</a></li>
            <li class="dropdown-submenu">
              <a class="dropdown-item d-flex justify-content-between align-items-center submenu-toggle" 
                 href="#" 
                 role="button">
                Profissionais <i class="bi bi-chevron-down small"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="agendamento.php">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="bate-papo.php">Bate-Papo</a></li>
                <li><a class="dropdown-item" href="meus-agendamentos.php">Meus Agendamentos</a></li>
              </ul>
            </li>
            <li><a class="dropdown-item" href="perfil.php">Perfil de Usuário</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../index.php">Sair</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo principal -->
  <main class="container mt-5 pt-5">
    <div class="gradient-card p-4">
      <h1 class="mb-4">Vídeos de Apoio</h1>
      
      <p class="lead mb-4">
        Aprenda mais sobre alimentação saudável com nossos vídeos educativos. Dicas, receitas e orientações para uma nutrição equilibrada!
      </p>

      <div class="row mb-4">
        <div class="col-md-8">
          <input type="search" class="form-control" placeholder="Pesquisar vídeos...">
        </div>
        <div class="col-md-4">
          <select class="form-select">
            <option selected>Todas as categorias</option>
            <option>Receitas</option>
            <option>Dicas</option>
            <option>Orientações</option>
          </select>
        </div>
      </div>

      <div class="row g-4">
        <!-- Vídeo 1 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/t6pK2j9-WI8" title="Receita saudável" allowfullscreen></iframe>
            </div>
            <h4>Receita saudável 1</h4>
            <p>Uma receita fácil e rápida para refeições balanceadas.</p>
            <span class="badge bg-primary">Receitas</span>
          </div>
        </div>
        
        <!-- Vídeo 2 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/ZF0MTDr0WQg" title="Dicas de nutrição" allowfullscreen></iframe>
            </div>
            <h4>Dicas de nutrição</h4>
            <p>Conselhos simples para melhorar sua alimentação.</p>
            <span class="badge bg-success">Dicas</span>
          </div>
        </div>
        
        <!-- Vídeo 3 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/9k_BjL6L1Qg" title="Orientações para dieta" allowfullscreen></iframe>
            </div>
            <h4>Orientações para dieta</h4>
            <p>Entenda como manter uma dieta equilibrada.</p>
            <span class="badge bg-warning text-dark">Orientações</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Aqui você pode adicionar a lógica para filtrar os vídeos
    document.querySelector('input[type="search"]').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      document.querySelectorAll('.gradient-card').forEach(card => {
        const title = card.querySelector('h4').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          card.closest('.col-md-6').style.display = '';
        } else {
          card.closest('.col-md-6').style.display = 'none';
        }
      });
    });
  </script>
  <script src="../js/videos.js" type="module"></script>
  <script type="module" src="../js/main.js"></script>
</body>
</html>