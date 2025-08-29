<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin (usando o valor REAL da sessão, não um valor forçado)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Definir os links corretos baseados no tipo REAL de usuário
if ($is_admin) {
    $link_agendamento = '../AdmLogado/agendamento-Adm.php';
    $link_meus_agendamentos = '../AdmLogado/meus-agendamentos-Adm.php';
} else {
    $link_agendamento = '../UsuarioLogado/agendamento.php';
    $link_meus_agendamentos = '../UsuarioLogado/meus-agendamentos.php';
}
?>

<div class="gradient-card p-4 table-container">
  <h1 class="mb-4">Meus Agendamentos</h1>
  
  <a href="<?php echo $link_agendamento; ?>" class="btn btn-primary mb-4">
    <i class="bi bi-plus-circle me-2"></i>Novo Agendamento
  </a>
  
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Profissional</th>
          <th>Data</th>
          <th>Horário</th>
          <th class="text-end">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Dr. Gabriel Vila - Nutricionista</td>
          <td>15/06/2023</td>
          <td>10:00</td>
          <td class="text-end">
            <div class="btn-group">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarAgendamento">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i> Excluir
              </button>
            </div>
          </td>
        </tr>
        <tr>
          <td>Dr. Gustavo Silva - Personal Trainer</td>
          <td>20/06/2023</td>
          <td>14:00</td>
          <td class="text-end">
            <div class="btn-group">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarAgendamento">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i> Excluir
              </button>
            </div>
          </td>
        </tr>
        <?php if ($is_admin): ?>
        <tr>
          <td>Dr. Silva - Administrador</td>
          <td>25/06/2023</td>
          <td>16:00</td>
          <td class="text-end">
            <div class="btn-group">
              <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarAgendamento">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i> Excluir
              </button>
            </div>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal para editar agendamento -->
<div class="modal fade" id="modalEditarAgendamento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Agendamento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarAgendamento">
          <div class="mb-3">
            <label for="editarProfissional" class="form-label">Profissional</label>
            <select class="form-select" id="editarProfissional" required>
              <option value="" selected disabled>Selecione um profissional</option>
              <option value="Dr. Gabriel Vila" selected>Dr. Gabriel Vila - Nutricionista</option>
              <option value="Dr. Gustavo Silva">Dr. Gustavo Silva - Personal Trainer</option>
              <option value="Dr. Julio Ribeiro">Dr. Julio Ribeiro - Endocrinologista</option>
              <?php if ($is_admin): ?>
              <option value="Dr. Silva">Dr. Silva - Administrador</option>
              <?php endif; ?>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="editarData" class="form-label">Data</label>
            <input type="date" class="form-control" id="editarData" value="2023-06-15" required>
          </div>
          
          <div class="mb-3">
            <label for="editarHora" class="form-label">Horário</label>
            <select class="form-select" id="editarHora" required>
              <option value="" selected disabled>Selecione um horário</option>
              <option value="10:00" selected>10:00</option>
              <option value="10:30">10:30</option>
              <option value="14:00">14:00</option>
              <option value="16:15">16:15</option>
              <?php if ($is_admin): ?>
              <option value="16:00">16:00 (Exclusivo Admin)</option>
              <?php endif; ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Salvar Alterações</button>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.btn-outline-danger').forEach(btn => {
  btn.addEventListener('click', function() {
    if (confirm('Tem certeza que deseja excluir este agendamento?')) {
      this.closest('tr').remove();
    }
  });
});
</script>