<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin (usando o valor REAL da sessão)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Definir os links corretos baseados no tipo REAL de usuário
if ($is_admin) {
    $link_editar_perfil = '../AdmLogado/editar-perfil-Adm.php';
} else {
    $link_editar_perfil = '../UsuarioLogado/editar-perfil.php';
}

// Dados do usuário (exemplo)
$usuario_nome = "Julio Ribeiro";
$usuario_email = "julioribeiro041@gmail.com";
$usuario_telefone = "(51) 99999-9999";
$usuario_foto = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
?>

<div class="gradient-card profile-container">
  <div class="text-center mb-4">
    <div class="profile-photo mx-auto"
      style="background-image: url('<?php echo $usuario_foto; ?>'); width: 150px; height: 150px;"
      aria-label="Foto do usuário <?php echo $usuario_nome; ?>"
      role="img"
    ></div>
  </div>

  <h2 class="text-center mb-4">Perfil do Usuário</h2>

  <div class="profile-info">
    <div class="mb-3">
      <label class="form-label fw-bold">Nome:</label>
      <div class="form-control bg-dark text-white"><?php echo $usuario_nome; ?></div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-bold">Email:</label>
      <div class="form-control bg-dark text-white"><?php echo $usuario_email; ?></div>
    </div>
    <div class="mb-3">
      <label class="form-label fw-bold">Telefone:</label>
      <div class="form-control bg-dark text-white"><?php echo $usuario_telefone; ?></div>
    </div>
  </div>

  <a href="<?php echo $link_editar_perfil; ?>" class="btn btn-primary w-100 mt-3 py-3">Editar Perfil</a>
</div>