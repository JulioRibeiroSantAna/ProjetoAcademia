<?php
/**
 * ARQUIVO: editar-perfil-dinamico.php
 * Formulário para editar dados do perfil
 * Permite alterar: nome, apelido, email, telefone, senha
 * Funciona para admin e usuário comum
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = ($_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'];
$msg = '';

// Busca dados atuais do banco
$stmt = $pdo->prepare('SELECT nome, apelido, email, telefone FROM usuarios WHERE id_usuario = ?');
$stmt->execute([$id_usuario]);
$user = $stmt->fetch();

// Processa formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $apelido = trim($_POST['apelido']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha']; // Senha é opcional
    
    $erros = [];
    
    // Valida campos
    if (strlen($nome) < 2) $erros[] = 'Nome muito curto';
    if (strlen($apelido) < 2) $erros[] = 'Apelido muito curto';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido';
    if ($senha && strlen($senha) < 3) $erros[] = 'Senha muito curta';
    
    // Verifica se email já está em uso por outro usuário
    $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?');
    $stmt->execute([$email, $id_usuario]);
    if ($stmt->fetch()) $erros[] = 'E-mail já usado por outro usuário';

    if (empty($erros)) {
        // Atualiza no banco
        if ($senha) {
            // Se digitou senha, atualiza ela também (criptografada)
            $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ?, senha = ? WHERE id_usuario = ?');
            $stmt->execute([$nome, $apelido, $email, $telefone, password_hash($senha, PASSWORD_DEFAULT), $id_usuario]);
        } else {
            // Se não digitou senha, mantém a antiga
            $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ? WHERE id_usuario = ?');
            $stmt->execute([$nome, $apelido, $email, $telefone, $id_usuario]);
        }
        
        // Atualiza sessão com novos dados
        $_SESSION['nome_usuario'] = $nome;
        $_SESSION['apelido_usuario'] = $apelido;
        $_SESSION['email_usuario'] = $email;
        $_SESSION['telefone_usuario'] = $telefone;
        
        $msg = 'Perfil atualizado com sucesso!';
        $user = ['nome' => $nome, 'apelido' => $apelido, 'email' => $email, 'telefone' => $telefone];
    } else {
        $msg = implode(', ', $erros);
    }
}
?>

<div class="mef-card">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
            <h3><?php echo $user['nome']; ?></h3>
        </div>
        
        <div class="col-md-8">
            <h4 class="mb-4">Editar Perfil</h4>
            
            <?php if ($msg): ?>
                <div class="alert alert-info"><?php echo $msg; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $user['nome']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apelido</label>
                    <input type="text" class="form-control" name="apelido" value="<?php echo $user['apelido']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-control" name="telefone" value="<?php echo $user['telefone']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                    <input type="password" class="form-control" name="senha">
                </div>
                
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo $is_admin ? '../AdmLogado/perfil-Adm.php' : '../UsuarioLogado/perfil.php'; ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>