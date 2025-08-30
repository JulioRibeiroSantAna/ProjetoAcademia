<?php
// includes-Gerais/perfil-dinamico.php

// Verificar se é admin ou usuário comum
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Definir dados do usuário
$usuario_nome = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Usuário';
$usuario_email = "julioribeiro04l@gmail.com";
$usuario_telefone = "(51) 99999-9999";
?>

<div class="mef-card">
    <div class="row">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <div class="mb-3">
                <img src="../assets/avatar-user.png" alt="Avatar do usuário" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
            <h3><?php echo $usuario_nome; ?></h3>
        </div>
        
        <div class="col-md-8">
            <h4 class="mb-4">Informações Pessoais</h4>
            
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="mef-form-label">Nome Completo</label>
                    <div class="mef-form-control d-flex align-items-center" style="min-height: 45px;">
                        <?php echo $usuario_nome; ?>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="mef-form-label">E-mail</label>
                    <div class="mef-form-control d-flex align-items-center" style="min-height: 45px; word-break: break-all;">
                        <?php echo $usuario_email; ?>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="mef-form-label">Telefone</label>
                    <div class="mef-form-control d-flex align-items-center" style="min-height: 45px;">
                        <?php echo $usuario_telefone; ?>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="mef-form-label">Data de Nascimento</label>
                    <div class="mef-form-control d-flex align-items-center" style="min-height: 45px;">
                        15/04/1990
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="<?php echo $is_admin ? '../AdmLogado/editar-perfil-Adm.php' : '../UsuarioLogado/editar-perfil.php'; ?>" class="mef-btn-primary text-decoration-none">
                    <i class="bi bi-pencil me-2"></i>Editar Perfil
                </a>
            </div>
        </div>
    </div>
</div>