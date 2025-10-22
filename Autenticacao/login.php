<?php
/**
 * ARQUIVO: login.php
 * Página de login do sistema
 * O usuário digita email e senha, sistema valida e redireciona
 */

// Carrega as configurações e inicia a sessão
require_once '../config.php';
require_once 'validacao.php';
require_once '../db_connection.php';

// Se veio da página de profissionais, mostra mensagem
if (isset($_GET['from']) && $_GET['from'] === 'profissionais') {
    $mensagem_redirect = 'Faça login para agendar uma consulta com nossos profissionais!';
}

// Processa o login quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Limpa os dados do formulário
    $dados = sanitizarEntrada($_POST);
    $email = $dados['email'] ?? '';
    $password = $dados['password'] ?? '';
    
    // Valida email e senha
    if (!validarEmail($email)) {
        $erro = 'E-mail inválido!';
    } elseif (!validarSenha($password)) {
        $erro = 'Senha deve ter pelo menos 3 caracteres!';
    } else {
        try {
            // Busca o usuário no banco
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                $senha_valida = false;
                
                // Verifica se a senha está criptografada ou em texto
                if (strlen($usuario['senha']) > 20 && strpos($usuario['senha'], '$') === 0) {
                    // Senha criptografada
                    $senha_valida = password_verify($password, $usuario['senha']);
                } else {
                    // Senha em texto (pra teste)
                    $senha_valida = ($password === $usuario['senha']);
                    
                    // Se está correta, criptografa ela agora
                    if ($senha_valida) {
                        $nova_senha_hash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt_update = $pdo->prepare('UPDATE usuarios SET senha = ? WHERE id_usuario = ?');
                        $stmt_update->execute([$nova_senha_hash, $usuario['id_usuario']]);
                    }
                }
                
                // Se a senha está correta, faz o login
                if ($senha_valida) {
                    // Salva dados na sessão
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['tipo_usuario'] = $usuario['tipo'];
                    $_SESSION['nome_usuario'] = $usuario['nome'];
                    $_SESSION['apelido_usuario'] = $usuario['apelido'];
                    $_SESSION['email_usuario'] = $usuario['email'];
                    $_SESSION['telefone_usuario'] = $usuario['telefone'];
                    $_SESSION['login_time'] = time();
                    
                    // Redireciona conforme o tipo de usuário
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
        
        <!-- Credenciais de teste -->
        <div class="alert alert-info mb-4">
            <strong>Credenciais para teste:</strong><br>
            <strong>Admin:</strong> admin@mef.com | 123<br>
            <strong>Usuário:</strong> Cadastre um novo usuário<br>
        </div>
        
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
                     placeholder="Sua senha" required>
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