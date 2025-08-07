<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Perfil - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
              <a class="dropdown-item d-flex justify-content-between align-items-center submenu-toggle" href="#" role="button">
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
    <div class="gradient-card mx-auto p-4" style="max-width: 500px;">
      <div class="text-center mb-4">
        <label for="fotoPerfil" class="profile-photo d-inline-block"
          style="background-image: url('https://cdn-icons-png.flaticon.com/512/3135/3135715.png'); width: 150px; height: 150px; cursor: pointer;"
          aria-label="Foto do usuário Julio Ribeiro"
          role="img"
        ></label>
        <input type="file" id="fotoPerfil" accept="image/*" class="d-none">
      </div>

      <h2 class="text-center mb-4">Editar Perfil</h2>

      <form id="formEditarPerfil">
        <div class="mb-3">
          <label for="nome" class="form-label">Nome:</label>
          <input type="text" class="form-control" id="nome" value="Julio Ribeiro" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" class="form-control" id="email" value="julioribeiro041@gmail.com" required>
        </div>

        <div class="mb-3">
          <label for="telefone" class="form-label">Telefone:</label>
          <input type="tel" class="form-control" id="telefone" value="(51) 99999-9999">
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Nova Senha (opcional):</label>
          <input type="password" class="form-control" id="senha" placeholder="Deixe em branco para não alterar">
        </div>

        <div class="mb-3">
          <label for="confirmarSenha" class="form-label">Confirmar Nova Senha:</label>
          <input type="password" class="form-control" id="confirmarSenha" placeholder="Repita a nova senha">
        </div>

        <button type="submit" class="btn btn-save w-100 mt-3">
          <i class="bi bi-save me-2"></i>Salvar Alterações
        </button>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Altera a foto de perfil ao selecionar um arquivo
    document.getElementById('fotoPerfil').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.querySelector('.profile-photo').style.backgroundImage = `url('${event.target.result}')`;
        };
        reader.readAsDataURL(file);
      }
    });

    // Validação do formulário
    document.getElementById('formEditarPerfil').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const senha = document.getElementById('senha').value;
      const confirmarSenha = document.getElementById('confirmarSenha').value;
      
      if (senha && senha !== confirmarSenha) {
        alert('As senhas não coincidem!');
        return;
      }
      
      alert('Perfil atualizado com sucesso!');
      // Aqui você pode adicionar o código para enviar os dados ao servidor
    });
  </script>
</body>
</html>