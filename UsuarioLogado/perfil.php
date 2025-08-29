<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Usuário - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .perfil-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .profile-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    /* Estilos específicos para perfil */
    .profile-container {
      max-width: 800px;
      margin: 40px auto;
      padding: 40px;
    }
    .profile-info .form-control {
      padding: 1rem 1.5rem !important;
      font-size: 1.1rem !important;
      background-color: rgba(255, 255, 255, 0.1) !important;
    }
    .form-label {
      font-size: 1.1rem !important;
      font-weight: 600 !important;
    }
    .btn-lg {
      padding: 1rem 2rem !important;
      font-size: 1.2rem !important;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container mt-5 pt-5 perfil-container">
    <div class="gradient-card profile-container">
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

      <a href="editar-perfil.php" class="btn btn-primary w-100 mt-3 py-3">Editar Perfil</a>
    </div>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>