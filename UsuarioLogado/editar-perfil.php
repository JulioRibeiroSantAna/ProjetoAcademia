<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Perfil - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .editar-perfil-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .form-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    /* Estilos específicos para formulários largos */
    .form-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 40px;
    }
    .form-control, .form-select {
      padding: 1rem 1.5rem !important;
      font-size: 1.1rem !important;
      border-radius: 12px !important;
    }
    .form-label {
      font-size: 1.1rem !important;
      font-weight: 600 !important;
      margin-bottom: 0.75rem !important;
    }
    .btn-lg {
      padding: 1rem 2rem !important;
      font-size: 1.2rem !important;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-5 editar-perfil-container">
    <div class="gradient-card form-container">
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

        <button type="submit" class="btn btn-save w-100 mt-3 py-3">
          <i class="bi bi-save me-2"></i>Salvar Alterações
        </button>
      </form>
    </div>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
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

    document.getElementById('formEditarPerfil').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const senha = document.getElementById('senha').value;
      const confirmarSenha = document.getElementById('confirmarSenha').value;
      
      if (senha && senha !== confirmarSenha) {
        alert('As senhas não coincidem!');
        return;
      }
      
      alert('Perfil atualizado com sucesso!');
    });
  </script>
  <script src="../js/menu.js"></script>
</body>
</html>