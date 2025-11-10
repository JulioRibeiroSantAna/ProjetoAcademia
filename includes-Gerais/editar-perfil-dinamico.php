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
$stmt = $pdo->prepare('SELECT nome, apelido, email, telefone, foto FROM usuarios WHERE id_usuario = ?');
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
    if ($senha && strlen($senha) < 8) $erros[] = 'Senha deve ter pelo menos 8 caracteres';
    
    // Verifica se email já está em uso por outro usuário
    $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?');
    $stmt->execute([$email, $id_usuario]);
    if ($stmt->fetch()) $erros[] = 'E-mail já usado por outro usuário';
    
    // Processa upload de foto
    $foto_path = $user['foto']; // Mantém foto atual por padrão
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $file_type = $_FILES['foto']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = __DIR__ . '/../uploads/usuarios/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Remove foto antiga se existir
            if ($user['foto'] && file_exists(__DIR__ . '/../' . $user['foto'])) {
                unlink(__DIR__ . '/../' . $user['foto']);
            }
            
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $new_filename = 'user_' . $id_usuario . '_' . time() . '.' . $ext;
            $foto_path = 'uploads/usuarios/' . $new_filename;
            
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $new_filename)) {
                $erros[] = 'Erro ao fazer upload da foto';
            }
        } else {
            $erros[] = 'Formato de imagem inválido. Use JPG, PNG ou GIF';
        }
    }

    if (empty($erros)) {
        // Atualiza no banco
        if ($senha) {
            // Se digitou senha, atualiza ela também (criptografada)
            $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ?, senha = ?, foto = ? WHERE id_usuario = ?');
            $stmt->execute([$nome, $apelido, $email, $telefone, password_hash($senha, PASSWORD_DEFAULT), $foto_path, $id_usuario]);
        } else {
            // Se não digitou senha, mantém a antiga
            $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ?, foto = ? WHERE id_usuario = ?');
            $stmt->execute([$nome, $apelido, $email, $telefone, $foto_path, $id_usuario]);
        }
        
        // Atualiza sessão com novos dados
        $_SESSION['nome_usuario'] = $nome;
        $_SESSION['apelido_usuario'] = $apelido;
        $_SESSION['email_usuario'] = $email;
        $_SESSION['telefone_usuario'] = $telefone;
        $_SESSION['foto_usuario'] = $foto_path;
        
        // Redireciona para o perfil (usando JavaScript pois já há output)
        $redirect_url = $is_admin ? '../AdmLogado/perfil-Adm.php?msg=success' : '../UsuarioLogado/perfil.php?msg=success';
        echo "<script>window.location.href = '$redirect_url';</script>";
        exit();
    } else {
        $msg = implode(', ', $erros);
    }
}
?>

<div class="mef-card">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0" style="max-width: 250px;">
            <div class="position-sticky" style="top: 20px;">
                <?php if (!empty($user['foto']) && file_exists(__DIR__ . '/../' . $user['foto'])): ?>
                    <img src="../<?php echo htmlspecialchars($user['foto']); ?>?v=<?php echo time(); ?>" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                <?php endif; ?>
                <h3 class="mb-0" style="font-size: 0.95rem; word-wrap: break-word; max-width: 200px; margin: 0 auto;"><?php echo htmlspecialchars($user['nome']); ?></h3>
            </div>
        </div>
        
        <div class="col-md-8" style="flex: 1;">
            <h4 class="mb-4">Editar Perfil</h4>
            
            <?php if ($msg): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Foto de Perfil</label>
                    <input type="file" class="form-control" name="foto" accept="image/*">
                    <small class="text-muted">Formatos aceitos: JPG, PNG, GIF</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apelido</label>
                    <input type="text" class="form-control" name="apelido" value="<?php echo htmlspecialchars($user['apelido']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-control" name="telefone" value="<?php echo htmlspecialchars($user['telefone']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                    <input type="password" class="form-control" name="senha" minlength="8" maxlength="14" placeholder="8-14 caracteres">
                </div>
                
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?php echo $is_admin ? '../AdmLogado/perfil-Adm.php' : '../UsuarioLogado/perfil.php'; ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>