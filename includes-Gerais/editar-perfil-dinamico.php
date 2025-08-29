<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin (usando o valor REAL da sessão)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Definir dados do usuário com base no tipo
if ($is_admin) {
    $usuario_nome = "Administrador";
    $usuario_email = "admin@mef.com";
    $usuario_telefone = "(51) 90000-0000";
    $titulo_pagina = "Editar Perfil - Administrador";
} else {
    $usuario_nome = "Julio Ribeiro";
    $usuario_email = "julioribeiro041@gmail.com";
    $usuario_telefone = "(51) 99999-9999";
    $titulo_pagina = "Editar Perfil";
}

$usuario_foto = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
?>

<div class="gradient-card form-container">
  <div class="text-center mb-4">
    <label for="fotoPerfil" class="profile-photo d-inline-block"
      style="background-image: url('<?php echo $usuario_foto; ?>'); width: 150px; height: 150px; cursor: pointer;"
      aria-label="Foto do <?php echo $is_admin ? 'administrador' : 'usuário ' . $usuario_nome; ?>"
      role="img"
    ></label>
    <input type="file" id="fotoPerfil" accept="image/*" class="d-none">
  </div>

  <h2 class="text-center mb-4"><?php echo $titulo_pagina; ?></h2>

  <form id="formEditarPerfil">
    <div class="mb-3">
      <label for="nome" class="form-label">Nome:</label>
      <input type="text" class="form-control" id="nome" value="<?php echo $usuario_nome; ?>" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input type="email" class="form-control" id="email" value="<?php echo $usuario_email; ?>" required>
    </div>

    <div class="mb-3">
      <label for="telefone" class="form-label">Telefone:</label>
      <input type="tel" class="form-control" id="telefone" value="<?php echo $usuario_telefone; ?>">
    </div>

    <div class="mb-3">
      <label for="senha" class="form-label">Nova Senha (opcional):</label>
      <input type="password" class="form-control" id="senha" placeholder="Deixe em branco para não alterar">
    </div>

    <div class="mb-3">
      <label for="confirmarSenha" class="form-label">Confirmar Nova Senha:</label>
      <input type="password" class="form-control" id="confirmarSenha" placeholder="Repita a nova senha">
    </div>

    <button type="submit" class="btn btn-primary w-100 mt-3 py-3">
      <i class="bi bi-save me-2"></i>Salvar Alterações
    </button>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const fotoPerfil = document.getElementById('fotoPerfil');
  const formEditarPerfil = document.getElementById('formEditarPerfil');
  
  if (fotoPerfil) {
    fotoPerfil.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          document.querySelector('.profile-photo').style.backgroundImage = `url('${event.target.result}')`;
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
    });
  }
});
</script>