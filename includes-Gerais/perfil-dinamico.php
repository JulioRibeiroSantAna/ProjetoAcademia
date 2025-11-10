<?php
/**
 * ARQUIVO: perfil-dinamico.php
 * Visualização do perfil do usuário
 * Mostra avatar, nome, apelido, email, telefone
 * Funciona para admin e usuário comum
 */

// Verifica se é admin (pra ajustar link de edição)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Pega dados da sessão
$usuario_nome = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Usuário';
$usuario_apelido = isset($_SESSION['apelido_usuario']) ? $_SESSION['apelido_usuario'] : '';
$usuario_email = isset($_SESSION['email_usuario']) ? $_SESSION['email_usuario'] : "";
$usuario_telefone = isset($_SESSION['telefone_usuario']) ? $_SESSION['telefone_usuario'] : "";
$usuario_foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : "";

// Usa apelido se tiver, senão usa nome completo
$nome_exibicao = !empty(trim($usuario_apelido)) ? $usuario_apelido : $usuario_nome;

// Verifica se há mensagem de sucesso
$show_success = isset($_GET['msg']) && $_GET['msg'] === 'success';
?>

<?php if ($show_success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>Perfil atualizado com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="mef-card">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <div class="mb-3">
                <?php if (!empty($usuario_foto) && file_exists(__DIR__ . '/../' . $usuario_foto)): ?>
                    <img src="../<?php echo htmlspecialchars($usuario_foto); ?>?v=<?php echo time(); ?>" alt="Avatar do usuário" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar do usuário" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                <?php endif; ?>
            </div>
            <h3><?php echo htmlspecialchars($nome_exibicao); ?></h3>
        </div>
        
        <div class="col-md-8">
            <h4 class="mb-4">Informações Pessoais</h4>
            
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Apelido</label>
                    <div class="form-control" style="min-height: 45px; background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                        <?php echo !empty(trim($usuario_apelido)) ? htmlspecialchars($usuario_apelido) : '<span class="text-muted">(não definido)</span>'; ?>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Nome Completo</label>
                    <div class="form-control" style="min-height: 45px; background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                        <?php echo htmlspecialchars($usuario_nome); ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-mail</label>
                    <div class="form-control" style="min-height: 45px; background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white; word-break: break-all;">
                        <?php echo htmlspecialchars($usuario_email); ?>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefone</label>
                    <div class="form-control" style="min-height: 45px; background-color: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                        <?php echo htmlspecialchars($usuario_telefone); ?>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="<?php echo $is_admin ? '../AdmLogado/editar-perfil-Adm.php' : '../UsuarioLogado/editar-perfil.php'; ?>" class="btn mef-btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar Perfil
                </a>
            </div>
        </div>
    </div>
</div>