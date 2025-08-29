<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determina a base URL dinamicamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = "$protocol://$host$script_path";

// Remove qualquer parte específica de pasta da base_url
$base_url = rtrim($base_url, '/');
if (strpos($base_url, '/AdmLogado') !== false) {
    $base_url = str_replace('/AdmLogado', '', $base_url);
}
if (strpos($base_url, '/UsuarioLogado') !== false) {
    $base_url = str_replace('/UsuarioLogado', '', $base_url);
}
if (strpos($base_url, '/Autenticacao') !== false) {
    $base_url = str_replace('/Autenticacao', '', $base_url);
}

// Verifica se é admin
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
?>

<div class="gradient-card agendamento-form" style="max-width: <?php echo $is_admin ? '600px' : '800px'; ?>; margin: <?php echo $is_admin ? '80px' : '40px'; ?> auto; padding: <?php echo $is_admin ? '30px' : '40px'; ?>;">
  <h1 class="text-center mb-4" style="color: white;">AGENDAMENTO</h1>
  <p class="text-center mb-4 <?php echo $is_admin ? '' : 'lead'; ?>">
    <strong>Preencha os campos para Agendar</strong>
  </p>
  
  <form id="formAgendamento">
    <div class="mb-4">
      <h3 style="color: white; <?php echo $is_admin ? 'border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px;' : 'border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 1rem;'; ?>">Dados Pessoais</h3>
      <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" required>
      </div>
      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" required>
      </div>
    </div>
    
    <div class="mb-4">
      <h3 style="color: white; <?php echo $is_admin ? 'border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px;' : 'border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 1rem;'; ?>">Agendamento</h3>
      <div class="mb-3">
        <label for="profissional" class="form-label">Profissional</label>
        <select class="form-select" id="profissional" required>
          <option value="" selected disabled>Selecione um profissional</option>
          <option value="Dr. Gabriel">Dr. Gabriel - Nutricionista</option>
          <option value="Dra. Ana">Dra. Ana - Nutricionista</option>
          <option value="Dr. Carlos">Dr. Carlos - Psicólogo</option>
          <?php if ($is_admin): ?>
          <option value="Dr. Silva">Dr. Silva - Administrador</option>
          <?php endif; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="data" class="form-label">Data</label>
        <select class="form-select" id="data" required>
          <option value="" selected disabled>Selecione uma data</option>
          <option value="2023-06-15">15/06/2023</option>
          <option value="2023-06-20">20/06/2023</option>
          <?php if ($is_admin): ?>
          <option value="2023-06-25">25/06/2023 (Exclusivo Admin)</option>
          <?php endif; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="hora" class="form-label">Hora</label>
        <select class="form-select" id="hora" required>
          <option value="" selected disabled>Selecione um horário</option>
          <option value="10:00">10:00</option>
          <option value="14:00">14:00</option>
          <?php if ($is_admin): ?>
          <option value="16:00">16:00 (Exclusivo Admin)</option>
          <?php endif; ?>
        </select>
      </div>
    </div>
    
    <button type="submit" class="btn btn-save w-100">AGENDAR CONSULTA</button>
  </form>
</div>

<script>
document.getElementById('formAgendamento').addEventListener('submit', function(e) {
  e.preventDefault();
  
  <?php if ($is_admin): ?>
  alert('Consulta agendada com sucesso! (Modo Administrador)');
  <?php else: ?>
  alert('Consulta agendada com sucesso!');
  <?php endif; ?>
});
</script>