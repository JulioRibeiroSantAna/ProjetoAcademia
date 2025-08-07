<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meus Agendamentos - MEF (Admin)</title>
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
    <div class="gradient-card p-4">
      <h1 class="mb-4">Meus Agendamentos</h1>
      
      <a href="agendamento-Adm.php" class="btn btn-primary mb-4">
        <i class="bi bi-plus-circle me-2"></i>Novo Agendamento
      </a>
      
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Profissional</th>
              <th>Data</th>
              <th>Horário</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Dr. Gabriel Vila - Nutricionista</td>
              <td>15/06/2023</td>
              <td>10:00</td>
              <td class="text-end">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarAgendamento">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Excluir
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td>Dr. Gustavo Silva - Personal Trainer</td>
              <td>20/06/2023</td>
              <td>14:00</td>
              <td class="text-end">
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarAgendamento">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i> Excluir
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal para editar agendamento -->
  <div class="modal fade" id="modalEditarAgendamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Editar Agendamento</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formEditarAgendamento">
            <div class="mb-3">
              <label for="editarProfissional" class="form-label">Profissional</label>
              <select class="form-select" id="editarProfissional" required>
                <option value="" selected disabled>Selecione um profissional</option>
                <option value="Dr. Gabriel Vila" selected>Dr. Gabriel Vila - Nutricionista</option>
                <option value="Dr. Gustavo Silva">Dr. Gustavo Silva - Personal Trainer</option>
                <option value="Dr. Julio Ribeiro">Dr. Julio Ribeiro - Endocrinologista</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label for="editarData" class="form-label">Data</label>
              <input type="date" class="form-control" id="editarData" value="2023-06-15" required>
            </div>
            
            <div class="mb-3">
              <label for="editarHora" class="form-label">Horário</label>
              <select class="form-select" id="editarHora" required>
                <option value="" selected disabled>Selecione um horário</option>
                <option value="10:00" selected>10:00</option>
                <option value="10:30">10:30</option>
                <option value="14:00">14:00</option>
                <option value="16:15">16:15</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // add a lógica para editar e excluir agendamentos
    document.querySelectorAll('.btn-outline-danger').forEach(btn => {
      btn.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja excluir este agendamento?')) {
          // Código para excluir o agendamento
          this.closest('tr').remove();
        }
      });
    });
  </script>
</body>
</html>