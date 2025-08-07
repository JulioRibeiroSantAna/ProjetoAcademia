<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento - MEF</title>
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

  <!-- Conteúdo principal -->
  <main class="container">
    <div class="gradient-card" style="max-width: 600px; margin: 80px auto; padding: 30px;">
      <h1 class="text-center mb-4" style="color: white;">AGENDAMENTO</h1>
      <p class="text-center mb-4"><strong>Preencha os campos para Agendar</strong></p>
      
      <form id="formAgendamento">
        <div class="mb-4">
          <h3 style="color: white; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px;">Dados Pessoais</h3>
          <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" id="nome" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="telefone" required>
          </div>
        </div>
        
        <div class="mb-4">
          <h3 style="color: white; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px;">Agendamento</h3>
          <div class="mb-3">
            <label for="profissional" class="form-label">Profissional</label>
            <select class="form-select" id="profissional" required>
              <option value="" selected disabled>Selecione um profissional</option>
              <option value="Dr. Gabriel">Dr. Gabriel - Nutricionista</option>
              <option value="Dra. Ana">Dra. Ana - Nutricionista</option>
              <option value="Dr. Carlos">Dr. Carlos - Psicólogo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <select class="form-select" id="data" required>
              <option value="" selected disabled>Selecione uma data</option>
              <option value="2023-06-15">15/06/2023</option>
              <option value="2023-06-20">20/06/2023</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <select class="form-select" id="hora" required>
              <option value="" selected disabled>Selecione um horário</option>
              <option value="10:00">10:00</option>
              <option value="14:00">14:00</option>
            </select>
          </div>
        </div>
        
        <button type="submit" class="btn btn-save w-100">AGENDAR CONSULTA</button>
      </form>
    </div>  
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('formAgendamento').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Consulta agendada com sucesso!');
    });
  </script>
</body>
</html>