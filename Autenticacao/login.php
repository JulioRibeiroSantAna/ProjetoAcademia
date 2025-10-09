<?php
// Autenticacao/login.php
require_once '../config.php';
require_once 'validacao.php';
require_once '../db_connection.php';

// Verificar se veio redirecionado da página de profissionais
if (isset($_GET['from']) && $_GET['from'] === 'profissionais') {
    $mensagem_redirect = 'Faça login para agendar uma consulta com nossos profissionais!';
}

// Processar login se for submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entradas
    $dados = sanitizarEntrada($_POST);
    $email = $dados['email'] ?? '';
    $password = $dados['password'] ?? '';
    
    // Validar dados
    if (!validarEmail($email)) {
        $erro = 'E-mail inválido!';
    } elseif (!validarSenha($password)) {
        $erro = 'Senha deve ter pelo menos 3 caracteres!';
    } else {
        try {
            // Buscar usuário
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verificar senha
                if (password_verify($password, $usuario['senha'])) {
                    // Login bem-sucedido
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo'];
                    $_SESSION['nome_usuario'] = $usuario['nome'];
                    $_SESSION['email_usuario'] = $usuario['email'];
                    $_SESSION['login_time'] = time();
                    
                    // Redirecionar conforme tipo de usuário
                    if ($usuario['tipo'] === 'admin') {
                        header('Location: ../AdmLogado/logado-Adm.php');
                    } elseif (in_array($usuario['tipo'], ['usuario', 'nutricionista'])) {
                        header('Location: ../UsuarioLogado/logado.php');
                    } else {
                        header('Location: ../index.php');
                    }
                    exit;
                } else {
                    $erro = 'E-mail ou senha incorretos!';
                }
            } else {
                $erro = 'E-mail ou senha incorretos!';
            }
        } catch (PDOException $e) {
            $erro = 'Erro no servidor. Tente novamente.';
            error_log("Erro login: " . $e->getMessage());
        }
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
          <div class="alert alert-success fade-in-up">
            <?php 
            echo $_SESSION['mensagem']; 
            unset($_SESSION['mensagem']);
            ?>
          </div>
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
              <input type="email" class="form-control mef-form-control" id="loginEmail" name="email" 
                     placeholder="Digite seu e-mail" required
                     value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
          </div>
          
          <div class="mb-4">
            <label for="loginPassword" class="mef-form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control mef-form-control" id="loginPassword" name="password" 
                     placeholder="Digite sua senha" required>
            </div>
          </div>
          
          <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn mef-btn-primary">
              <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
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
</body>
</html>