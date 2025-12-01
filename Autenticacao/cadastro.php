<?php
header('Content-Type: text/html; charset=UTF-8');
/**
 * Página de cadastro de novos usuários
 */

require_once '../config.php';
require_once 'validacao.php';
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $dados = sanitizarEntrada($_POST);
    $nome = $dados['name'] ?? '';
    $apelido = $dados['apelido'] ?? '';
    $email = $dados['email'] ?? '';
    $senha = $dados['password'] ?? '';
    $confirmSenha = $dados['confirmPassword'] ?? '';
    $telefone = $dados['telefone'] ?? '';
    $tipo = 'usuario';

    $erros = validarCadastro($nome, $apelido, $email, $senha, $confirmSenha, $telefone);
    
    if (empty($erros)) {
        try {
            $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $_SESSION['mensagem'] = 'E-mail já cadastrado!';
                $_SESSION['tipo_mensagem'] = 'erro';
            } else {
                $hash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO usuarios (nome, apelido, email, senha, tipo, telefone) VALUES (?, ?, ?, ?, ?, ?)');
                
                if ($stmt->execute([$nome, $apelido, $email, $hash, $tipo, $telefone])) {
                    $_SESSION['mensagem'] = 'Cadastro realizado com sucesso! Faça login.';
                    $_SESSION['tipo_mensagem'] = 'sucesso';
                    header('Location: login.php');
                    exit;
                } else {
                    $_SESSION['mensagem'] = 'Erro ao cadastrar usuário!';
                    $_SESSION['tipo_mensagem'] = 'erro';
                }
            }
        } catch (PDOException $e) {
            $_SESSION['mensagem'] = 'Erro no servidor. Tente novamente.';
            $_SESSION['tipo_mensagem'] = 'erro';
            error_log("Erro cadastro: " . $e->getMessage());
        }
    } else {
        $_SESSION['mensagem'] = implode('<br>', $erros);
        $_SESSION['tipo_mensagem'] = 'erro';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - MEF</title>
  <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <!-- Conteúdo Principal -->
  <main class="auth-container">
    <div class="container-symmetric">
      <div class="mef-card auth-card">
        <h1 class="text-center mb-4 fade-in-up">Cadastro</h1>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
          <div class="alert alert-<?php echo ($_SESSION['tipo_mensagem'] === 'sucesso') ? 'success' : 'danger'; ?> fade-in-up">
            <?php 
            echo $_SESSION['mensagem']; 
            unset($_SESSION['mensagem']);
            unset($_SESSION['tipo_mensagem']);
            ?>
          </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="fade-in-up">
          <div class="mb-3">
            <label for="name" class="mef-form-label">Nome Completo</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control mef-form-control" id="name" name="name" 
                     placeholder="Digite seu nome completo" required
                     value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label for="apelido" class="mef-form-label">Apelido (nome curto)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
              <input type="text" class="form-control mef-form-control" id="apelido" name="apelido" 
                     placeholder="Digite um apelido curto" required
                     value="<?php echo htmlspecialchars($_POST['apelido'] ?? ''); ?>">
            </div>
          </div>
          
          <div class="mb-3">
            <label for="email" class="mef-form-label">E-mail</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control mef-form-control" id="email" name="email" 
                     placeholder="Digite seu e-mail" required
                     value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
          </div>
          
          <div class="mb-3">
            <label for="telefone" class="mef-form-label">Telefone</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              <input type="tel" class="form-control mef-form-control" id="telefone" name="telefone" 
                     placeholder="Digite seu telefone" required value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>">
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="password" class="mef-form-label">Senha</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control mef-form-control" id="password" name="password" 
                       placeholder="8-14 caracteres" minlength="8" maxlength="14" required>
              </div>
            </div>
            <div class="col-md-6">
              <label for="confirmPassword" class="mef-form-label">Confirme a Senha</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control mef-form-control" id="confirmPassword" name="confirmPassword" 
                       placeholder="Repita a senha" minlength="8" maxlength="14" required>
              </div>
            </div>
          </div>
          
          <div class="d-grid gap-2 mb-4">
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
    </div>
  </main>

  <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Validação básica de confirmação de senha
    document.querySelector('form').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
      }
      
      if (password.length < 3) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 3 caracteres!');
        return false;
      }
    });
  </script>
</body>
</html>