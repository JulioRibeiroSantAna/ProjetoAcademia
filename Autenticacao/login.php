<?php
// Autenticacao/login.php
require_once '../config.php';

// Verificar se veio redirecionado da página de profissionais
if (isset($_GET['from']) && $_GET['from'] === 'profissionais') {
    $mensagem_redirect = 'Faça login para agendar uma consulta com nossos profissionais!';
}

// Processar login se for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Logins provisórios
    if ($email === 'admin@mef.com' && $password === 'admin123') {
        $_SESSION['tipo_usuario'] = 'admin';
        $_SESSION['nome_usuario'] = 'Administrador';
        header('Location: ../AdmLogado/logado-Adm.php');
        exit;
    }
    elseif ($email === 'usuario@mef.com' && $password === 'user123') {
        $_SESSION['tipo_usuario'] = 'usuario';
        $_SESSION['nome_usuario'] = 'Usuário Teste';
        header('Location: ../UsuarioLogado/logado.php');
        exit;
    }
    else {
        $erro = 'E-mail ou senha inválidos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MEF</title>
  <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <!-- Conteúdo Principal -->
  <main class="auth-container">
    <div class="container-symmetric">
      <div class="mef-card auth-card">
        <h1 class="text-center mb-4 fade-in-up">Login</h1>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
          <div class="alert alert-success fade-in-up"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($mensagem_redirect)): ?>
          <div class="alert alert-info fade-in-up">
            <i class="bi bi-info-circle me-2"></i><?php echo $mensagem_redirect; ?>
          </div>
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
          <div class="alert alert-danger fade-in-up">
            <i class="bi bi-exclamation-triangle me-2"></i><?php echo $erro; ?>
          </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="fade-in-up">
          <div class="mb-4">
            <label for="loginEmail" class="mef-form-label">E-mail</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control mef-form-control" id="loginEmail" name="email" placeholder="Digite seu e-mail" required>
            </div>
          </div>
          
          <div class="mb-4">
            <label for="loginPassword" class="mef-form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control mef-form-control" id="loginPassword" name="password" placeholder="Digite sua senha" required>
            </div>
          </div>
          
          <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn mef-btn-primary">
              <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
          </div>
          
          <!-- Botões provisórios para teste -->
          <div class="row mb-4">
            <div class="col-md-6 mb-2">
              <button type="button" class="btn btn-outline-warning w-100" onclick="preencherLogin('admin')">
                <i class="bi bi-person-gear me-2"></i>Login Admin
              </button>
            </div>
            <div class="col-md-6">
              <button type="button" class="btn btn-outline-info w-100" onclick="preencherLogin('usuario')">
                <i class="bi bi-person me-2"></i>Login Usuário
              </button>
            </div>
          </div>
          
          <div class="text-center">
            <a href="cadastro.php" class="btn btn-outline-light w-100">
              <i class="bi bi-person-plus me-2"></i>Não tem conta? Cadastre-se
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function preencherLogin(tipo) {
      if (tipo === 'admin') {
        document.getElementById('loginEmail').value = 'admin@mef.com';
        document.getElementById('loginPassword').value = 'admin123';
      } else if (tipo === 'usuario') {
        document.getElementById('loginEmail').value = 'usuario@mef.com';
        document.getElementById('loginPassword').value = 'user123';
      }
      // Auto-submit do formulário
      document.querySelector('form').submit();
    }
  </script>
</body>
</html>