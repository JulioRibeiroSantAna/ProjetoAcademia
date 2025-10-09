<?php
// includes-Gerais/editar-perfil-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';
require_once __DIR__ . '/../Autenticacao/validacao.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'] ?? null;
$usuario_nome = '';
$usuario_email = '';
$usuario_telefone = '';
$usuario_data_nascimento = '';
$usuario_foto = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
$msg = '';
$msg_tipo = '';

// Buscar dados do usuário no banco
if ($id_usuario) {
    $stmt = $pdo->prepare('SELECT nome, apelido, email, telefone FROM usuarios WHERE id_usuario = ?');
    $stmt->execute([$id_usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $usuario_nome = $user['nome'];
        $usuario_apelido = $user['apelido'];
        $usuario_email = $user['email'];
        $usuario_telefone = $user['telefone'];
    }
}

// Atualizar dados se enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = sanitizarEntrada($_POST);
    $novo_nome = $dados['nome'] ?? '';
    $novo_apelido = $dados['apelido'] ?? '';
    $novo_email = $dados['email'] ?? '';
    $novo_telefone = $dados['telefone'] ?? '';
    $nova_senha = $dados['senha'] ?? '';
    $confirmar_senha = $dados['confirmarSenha'] ?? '';

    $erros = [];
    if (!validarNome($novo_nome)) $erros[] = 'Nome inválido.';
    if (!validarApelido($novo_apelido)) $erros[] = 'Apelido inválido.';
    if (!validarEmail($novo_email)) $erros[] = 'E-mail inválido.';
    if (!validarTelefone($novo_telefone)) $erros[] = 'Telefone inválido.';
    if ($nova_senha && $nova_senha !== $confirmar_senha) $erros[] = 'As senhas não coincidem!';
    if ($nova_senha && !validarSenha($nova_senha)) $erros[] = 'A senha deve ter pelo menos 3 caracteres!';

    // Verificar se o e-mail já existe para outro usuário
    $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?');
    $stmt->execute([$novo_email, $id_usuario]);
    if ($stmt->fetch()) $erros[] = 'E-mail já cadastrado por outro usuário!';

    if (empty($erros)) {
        $sql = 'UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ?';
        $params = [$novo_nome, $novo_apelido, $novo_email, $novo_telefone];
        if ($nova_senha) {
            $sql .= ', senha = ?';
            $params[] = password_hash($nova_senha, PASSWORD_DEFAULT);
        }
        $sql .= ' WHERE id_usuario = ?';
        $params[] = $id_usuario;
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            $msg = 'Perfil atualizado com sucesso!';
            $msg_tipo = 'success';
            $_SESSION['nome_usuario'] = $novo_nome;
            $_SESSION['apelido_usuario'] = $novo_apelido;
            $_SESSION['email_usuario'] = $novo_email;
            $_SESSION['telefone_usuario'] = $novo_telefone;
            $usuario_nome = $novo_nome;
            $usuario_apelido = $novo_apelido;
            $usuario_email = $novo_email;
            $usuario_telefone = $novo_telefone;
        } else {
            $msg = 'Erro ao atualizar perfil!';
            $msg_tipo = 'danger';
        }
    } else {
        $msg = implode('<br>', $erros);
        $msg_tipo = 'danger';
    }
}
?>

<div class="mef-card">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <div class="mb-3">
                <label for="fotoPerfil" class="d-inline-block cursor-pointer">
                    <img src="<?php echo $usuario_foto; ?>" alt="Avatar do usuário" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;">
                </label>
                <input type="file" id="fotoPerfil" accept="image/*" class="d-none">
            </div>
            <h3><?php echo $usuario_nome; ?></h3>
        </div>
        
        <div class="col-md-8">
            <h4 class="mb-4">Editar Informações Pessoais</h4>
            
            <?php if ($msg): ?>
                <div class="alert alert-<?php echo $msg_tipo; ?> fade-in-up"><?php echo $msg; ?></div>
            <?php endif; ?>
            <form id="formEditarPerfil" method="POST" action="">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nome" class="mef-form-label">Nome Completo</label>
                        <input type="text" class="mef-form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario_nome); ?>" required>
                    </div>
                        <div class="col-12 mb-3">
                            <label for="apelido" class="mef-form-label">Apelido (nome curto)</label>
                            <input type="text" class="mef-form-control" id="apelido" name="apelido" value="<?php echo htmlspecialchars($usuario_apelido ?? ''); ?>" required>
                        </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="mef-form-label">E-mail</label>
                        <input type="email" class="mef-form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario_email); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="mef-form-label">Telefone</label>
                        <input type="tel" class="mef-form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario_telefone); ?>">
                    </div>
                    <!-- Campos de senha lado a lado -->
                    <div class="col-md-6 mb-3">
                        <label for="senha" class="mef-form-label">Nova Senha (opcional)</label>
                        <input type="password" class="mef-form-control" id="senha" name="senha" placeholder="Deixe em branco para não alterar">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirmarSenha" class="mef-form-label">Confirmar Nova Senha</label>
                        <input type="password" class="mef-form-control" id="confirmarSenha" name="confirmarSenha" placeholder="Repita a nova senha">
                    </div>
                </div>
                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="mef-btn-primary">
                        <i class="bi bi-save me-2"></i>Salvar Alterações
                    </button>
                    <a href="<?php echo $is_admin ? '../AdmLogado/perfil-Adm.php' : '../UsuarioLogado/perfil.php'; ?>" class="btn btn-secondary d-flex align-items-center">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoPerfil = document.getElementById('fotoPerfil');
    const formEditarPerfil = document.getElementById('formEditarPerfil');
    
    // Click na foto para abrir o seletor de arquivos
    document.querySelector('.cursor-pointer').addEventListener('click', function() {
        fotoPerfil.click();
    });
    
    if (fotoPerfil) {
        fotoPerfil.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.cursor-pointer img').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Remover submit JS, pois agora é processado no backend
});
</script>