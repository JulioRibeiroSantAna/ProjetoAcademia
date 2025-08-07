<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MEF - Área Logada</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  
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
            <a class="nav-link" href="#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#fale-conosco">Fale Conosco</a>
          </li>
        </ul>

        <!-- Menu do usuário -->
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

  <!-- Conteúdo Principal -->
  <main>
    <!-- Seção Hero -->
    <section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">Bem-vindo(a) ao MEF</h1>
        <h2 class="h3 mb-5">Área do Usuário</h2>
        <div class="d-flex justify-content-center gap-3">
          <a href="agendamento.php" class="btn btn-primary btn-lg">Agendar Consulta</a>
          <a href="bate-papo.php" class="btn btn-outline-light btn-lg">Bate-Papo</a>
        </div>
      </div>
    </section>

    <!-- Sobre -->
    <section id="sobre" class="gradient-card py-5">
      <div class="container">
        <h2 class="text-center mb-5">SOBRE NOSSA PLATAFORMA</h2>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <p class="lead text-center">Bem-vindo ao MEF - sua plataforma de saúde e bem-estar!</p>
            <p>Aqui você encontra tudo o que precisa para melhorar sua saúde e qualidade de vida. Nosso sistema permite que você:</p>
            <ul class="mb-4">
              <li>Agende consultas com profissionais qualificados</li>
              <li>Tire dúvidas sobre nutrição e saúde</li>
              <li>Acesse conteúdos exclusivos sobre alimentação saudável</li>
              <li>Receba orientações personalizadas</li>
            </ul>
            <p>Descubra recomendações de alimentos, receitas nutritivas e vídeos explicativos sobre exercícios físicos.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Profissionais -->
    <section id="profissionais" class="gradient-card py-5">
      <div class="container">
        <h2 class="text-center mb-5">NOSSOS PROFISSIONAIS</h2>
        <p class="text-center lead mb-5">Conheça nossa equipe de especialistas</p>

        <div class="row g-4">
          <!-- Profissional 1 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gabriel da Vila">
              <div class="card-body text-center">
                <h5 class="card-title">Gabriel da Vila</h5>
                <p class="card-text text-muted">Nutricionista Esportivo</p>
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 2 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gustavo Silva">
              <div class="card-body text-center">
                <h5 class="card-title">Gustavo Silva</h5>
                <p class="card-text text-muted">Personal Trainer</p>
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 3 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Julio Ribeiro">
              <div class="card-body text-center">
                <h5 class="card-title">Julio Ribeiro</h5>
                <p class="card-text text-muted">Endocrinologista</p>
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 4 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Miqueias">
              <div class="card-body text-center">
                <h5 class="card-title">Miqueias</h5>
                <p class="card-text text-muted">Psicólogo</p>
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Fale Conosco -->
    <section id="fale-conosco" class="gradient-card py-5">
      <div class="container text-center">
        <h2 class="mb-4">FALE CONOSCO</h2>
        <p class="lead mb-4">Tem dúvidas ou sugestões? Entre em contato conosco!</p>
        
        <div class="d-flex justify-content-center gap-3 mb-4">
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-instagram fs-4"></i>
          </a>
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-facebook fs-4"></i>
          </a>
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-whatsapp fs-4"></i>
          </a>
        </div>
        
        <p class="mb-4">
          <i class="bi bi-envelope me-2"></i>
          <a href="mailto:contato@mef.com.br" class="text-white">contato@mef.com.br</a>
        </p>
        
        <a href="#" class="btn btn-primary px-4 py-2">
          <i class="bi bi-headset me-2"></i> Chat Online
        </a>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <footer class="gradient-card text-white py-4">
    <div class="container text-center">
      <p class="mb-0">© 2023 MEF - Todos os direitos de Juliusss</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Seus scripts -->
  <script type="module" src="../js/main.js"></script>
</body>
</html>