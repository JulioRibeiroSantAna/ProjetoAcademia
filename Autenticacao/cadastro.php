<?php
// Autenticacao/cadastro.php
require_once '../config.php';

// Processar formulário se for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica de cadastro (será implementada posteriormente)
    $_SESSION['mensagem'] = 'Cadastro realizado com sucesso! Faça login.';
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.php">MEF</a>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <main class="container mt-5 pt-5">
    <div class="gradient-card p-4" style="max-width: 600px; margin: 0 auto;">
      <h1 class="mb-4 text-center">Cadastro</h1>
      
      <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
      <?php endif; ?>
      
      <form method="POST" action="">
        <div class="row mb-3">
          <div class="col-md-6 mb-3 mb-md-0">
            <label for="name" class="form-label">Nome Completo</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="username" class="form-label">Nome de Usuário</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
              <input type="text" class="form-control" id="username" name="username" placeholder="Escolha um usuário" required>
            </div>
          </div>
        </div>
        
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
          </div>
        </div>
        
        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <label for="password" class="form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" id="password" name="password" placeholder="Crie uma senha" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="confirmPassword" class="form-label">Confirme a Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Repita a senha" required>
            </div>
          </div>
        </div>
        
        <div class="d-grid gap-2 mb-3">
          <button type="submit" class="btn btn-save">
            <i class="bi bi-person-plus me-2"></i>Criar Conta
          </button>
        </div>
        
        <div class="text-center">
          <a href="login.php" class="btn btn-outline-light w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Já tem conta? Faça login
          </a>
        </div>
      </form>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validação básica de confirmação de senha
    document.querySelector('form').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('As senhas não coincidem!');
      }
    });
  </script>
</body>
</html>