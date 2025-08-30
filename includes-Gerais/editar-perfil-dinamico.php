<?php
// includes-Gerais/editar-perfil-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Definir dados do usuário com base no tipo
if ($is_admin) {
    $usuario_nome = "Administrador";
    $usuario_email = "admin@mef.com";
    $usuario_telefone = "(51) 90000-0000";
    $usuario_data_nascimento = "01/01/1980";
} else {
    $usuario_nome = "Julio Ribeiro";
    $usuario_email = "julioribeiro04l@gmail.com";
    $usuario_telefone = "(51) 99999-9999";
    $usuario_data_nascimento = "15/04/1990";
}

$usuario_foto = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
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
            
            <form id="formEditarPerfil">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nome" class="mef-form-label">Nome Completo</label>
                        <input type="text" class="mef-form-control" id="nome" value="<?php echo $usuario_nome; ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="mef-form-label">E-mail</label>
                        <input type="email" class="mef-form-control" id="email" value="<?php echo $usuario_email; ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="mef-form-label">Telefone</label>
                        <input type="tel" class="mef-form-control" id="telefone" value="<?php echo $usuario_telefone; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="dataNascimento" class="mef-form-label">Data de Nascimento</label>
                        <input type="text" class="mef-form-control" id="dataNascimento" value="<?php echo $usuario_data_nascimento; ?>">
                    </div>
                    
                    <!-- Espaço vazio para alinhar com a data de nascimento -->
                    <div class="col-md-6 mb-3"></div>
                    
                    <!-- Campos de senha lado a lado -->
                    <div class="col-md-6 mb-3">
                        <label for="senha" class="mef-form-label">Nova Senha (opcional)</label>
                        <input type="password" class="mef-form-control" id="senha" placeholder="Deixe em branco para não alterar">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="confirmarSenha" class="mef-form-label">Confirmar Nova Senha</label>
                        <input type="password" class="mef-form-control" id="confirmarSenha" placeholder="Repita a nova senha">
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
    
    if (formEditarPerfil) {
        formEditarPerfil.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const senha = document.getElementById('senha').value;
            const confirmarSenha = document.getElementById('confirmarSenha').value;
            
            if (senha && senha !== confirmarSenha) {
                alert('As senhas não coincidem!');
                return;
            }
            
            alert('Perfil <?php echo $is_admin ? "do administrador" : ""; ?> atualizado com sucesso!');
            window.location.href = '<?php echo $is_admin ? "../AdmLogado/perfil-Adm.php" : "../UsuarioLogado/perfil.php"; ?>';
        });
    }
});
</script>