<?php
/**
 * Página de login
 */

require_once '../config.php';
require_once 'validacao.php';
require_once '../db_connection.php';

if (isset($_GET['from']) && $_GET['from'] === 'profissionais') {
    $mensagem_redirect = 'Faça login para agendar uma consulta com nossos profissionais!';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $dados = sanitizarEntrada($_POST);
    $email = $dados['email'] ?? '';
    $password = $dados['password'] ?? '';
    
    if (!validarEmail($email)) {
        $erro = 'E-mail inválido!';
    } elseif (!validarSenha($password)) {
        $erro = 'Senha deve ter entre 8 e 14 caracteres!';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                $senha_valida = false;
                
                if (strlen($usuario['senha']) > 20 && strpos($usuario['senha'], '$') === 0) {
                    $senha_valida = password_verify($password, $usuario['senha']);
                } else {
                    $senha_valida = ($password === $usuario['senha']);
                    
                    if ($senha_valida) {
                        $nova_senha_hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt_update = $pdo->prepare('UPDATE usuarios SET senha = ? WHERE id_usuario = ?');
                        $stmt_update->execute([$nova_senha_hash, $usuario['id_usuario']]);
                    }
                }
                
                if ($senha_valida) {
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo'];
                    $_SESSION['nome_usuario'] = $usuario['nome'];
                    $_SESSION['apelido_usuario'] = $usuario['apelido'];
                    $_SESSION['email_usuario'] = $usuario['email'];
                    $_SESSION['telefone_usuario'] = $usuario['telefone'];
                    $_SESSION['foto_usuario'] = $usuario['foto'];
                    $_SESSION['login_time'] = time();
                    
                    if ($usuario['tipo'] === 'admin') {
                        header('Location: ../AdmLogado/logado-Adm.php');
                    } else {
                        header('Location: ../UsuarioLogado/logado.php');
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
  
  <!-- Estilos: Bootstrap + Ícones + Fonte Poppins + CSS customizado -->
  <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="auth-container">
    <div class="container-symmetric">
      <div class="mef-card auth-card">
        <h1 class="text-center mb-4 fade-in-up">Login</h1>
        
        <!-- Mensagem de sucesso do cadastro -->
        <?php if (isset($_SESSION['mensagem'])): ?>
          <div class="alert alert-success fade-in-up">
            <?php 
            echo $_SESSION['mensagem'];
            unset($_SESSION['mensagem']);
            ?>
          </div>
        <?php endif; ?>
        
        <!-- Mensagem de redirecionamento -->
        <?php if (isset($mensagem_redirect)): ?>
          <div class="alert alert-info fade-in-up">
            <?php echo htmlspecialchars($mensagem_redirect); ?>
          </div>
        <?php endif; ?>
        
        <!-- Mensagem de erro -->
        <?php if (isset($erro)): ?>
          <div class="alert alert-danger fade-in-up">
            <?php echo htmlspecialchars($erro); ?>
          </div>
        <?php endif; ?>
        
        <!-- Formulário de Login -->
        <form method="POST" action="" class="fade-in-up">
          <!-- Campo Email -->
          <div class="mb-4">
            <label for="email" class="mef-form-label">E-mail</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control mef-form-control" id="email" name="email" 
                     placeholder="seu@email.com" required>
            </div>
          </div>
          
          <!-- Campo Senha -->
          <div class="mb-4">
            <label for="password" class="mef-form-label">Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control mef-form-control" id="password" name="password" 
                     placeholder="Sua senha (8-14 caracteres)" minlength="8" maxlength="14" required>
            </div>
          </div>
          
          <!-- Botão Entrar -->
          <div class="d-grid gap-2 mb-4">
            <button type="submit" class="btn mef-btn-primary btn-lg">
              <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
            </button>
          </div>
          
          <!-- Link Cadastro -->
          <div class="text-center">
            <a href="cadastro.php" class="btn btn-outline-light w-100">
              <i class="bi bi-person-plus me-2"></i>Criar nova conta
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>