<?php
// includes-Gerais/meus-agendamentos-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'] ?? null;
$msg = '';

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'excluir') {
        $id_agendamento = $_POST['id_agendamento'] ?? 0;
        try {
            if ($is_admin) {
                $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id_agendamento = ?");
                $stmt->execute([$id_agendamento]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM agendamentos WHERE id_agendamento = ? AND id_usuario = ?");
                $stmt->execute([$id_agendamento, $id_usuario]);
            }
            $msg = 'Agendamento excluído com sucesso!';
        } catch (PDOException $e) {
            $msg = 'Erro ao excluir agendamento!';
        }
    }
    
    if ($acao === 'editar') {
        $id_agendamento = $_POST['id_agendamento'] ?? 0;
        $nova_data = $_POST['nova_data'] ?? '';
        $nova_hora = $_POST['nova_hora'] ?? '';
        
        if ($nova_data && $nova_hora) {
            $nova_data_hora = $nova_data . ' ' . $nova_hora . ':00';
            
            try {
                if ($is_admin) {
                    $stmt = $pdo->prepare("UPDATE agendamentos SET data_hora = ? WHERE id_agendamento = ?");
                    $stmt->execute([$nova_data_hora, $id_agendamento]);
                } else {
                    $stmt = $pdo->prepare("UPDATE agendamentos SET data_hora = ? WHERE id_agendamento = ? AND id_usuario = ?");
                    $stmt->execute([$nova_data_hora, $id_agendamento, $id_usuario]);
                }
                $msg = 'Agendamento atualizado com sucesso!';
            } catch (PDOException $e) {
                $msg = 'Erro ao atualizar agendamento!';
            }
        }
    }
}

// Buscar agendamentos
$agendamentos = [];
if ($id_usuario) {
    try {
        if ($is_admin) {
            // Admin vê todos os agendamentos
            $stmt = $pdo->query("
                SELECT a.*, u.nome as usuario_nome, p.nome as profissional_nome, p.especialidade
                FROM agendamentos a
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                LEFT JOIN profissionais p ON a.id_nutricionista = p.id
                ORDER BY a.data_hora DESC
            ");
        } else {
            // Usuário vê apenas seus agendamentos
            $stmt = $pdo->prepare("
                SELECT a.*, p.nome as profissional_nome, p.especialidade
                FROM agendamentos a
                LEFT JOIN profissionais p ON a.id_nutricionista = p.id
                WHERE a.id_usuario = ?
                ORDER BY a.data_hora DESC
            ");
            $stmt->execute([$id_usuario]);
        }
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $agendamentos = [];
    }
}

$link_agendamento = $is_admin ? '../AdmLogado/agendamento-Adm.php' : '../UsuarioLogado/agendamento.php';
?>

<div class="mef-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?php echo $is_admin ? 'Todos os Agendamentos' : 'Meus Agendamentos'; ?></h1>
        <a href="<?php echo $link_agendamento; ?>" class="btn mef-btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Novo Agendamento
        </a>
    </div>
    
    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>
    
    <?php if (empty($agendamentos)): ?>
        <div class="text-center py-5">
            <i class="bi bi-calendar-x display-1 text-muted"></i>
            <h3 class="mt-3">Nenhum agendamento encontrado</h3>
            <p class="text-muted">Que tal agendar sua primeira consulta?</p>
            <a href="<?php echo $link_agendamento; ?>" class="btn mef-btn-primary mt-3">
                Agendar Consulta
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table mef-table">
                <thead>
                    <tr>
                        <?php if ($is_admin): ?>
                            <th>Cliente</th>
                        <?php endif; ?>
                        <th>Profissional</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <?php 
                        $data_hora = new DateTime($agendamento['data_hora']);
                        $data_formatada = $data_hora->format('d/m/Y');
                        $hora_formatada = $data_hora->format('H:i');
                        ?>
                        <tr>
                            <?php if ($is_admin): ?>
                                <td><?php echo htmlspecialchars($agendamento['usuario_nome'] ?? 'N/A'); ?></td>
                            <?php endif; ?>
                            <td>
                                <?php echo htmlspecialchars($agendamento['profissional_nome'] ?? 'Profissional'); ?>
                                <?php if ($agendamento['especialidade']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($agendamento['especialidade']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $data_formatada; ?></td>
                            <td><?php echo $hora_formatada; ?></td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editarAgendamento(<?php echo $agendamento['id_agendamento']; ?>, '<?php echo $data_hora->format('Y-m-d'); ?>', '<?php echo $data_hora->format('H:i'); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="excluirAgendamento(<?php echo $agendamento['id_agendamento']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para editar agendamento -->
<div class="modal fade" id="modalEditarAgendamento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Agendamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id_agendamento" id="edit_id_agendamento">
                    
                    <div class="mb-3">
                        <label for="editarData" class="form-label">Nova Data</label>
                        <input type="date" class="form-control" name="nova_data" id="editarData" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editarHora" class="form-label">Novo Horário</label>
                        <select class="form-select" name="nova_hora" id="editarHora" required>
                            <option value="">Selecione um horário</option>
                            <option value="08:00">08:00</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarAgendamento(id, data, hora) {
    document.getElementById('edit_id_agendamento').value = id;
    document.getElementById('editarData').value = data;
    document.getElementById('editarHora').value = hora;
    
    const modal = new bootstrap.Modal(document.getElementById('modalEditarAgendamento'));
    modal.show();
}

function excluirAgendamento(id) {
    if (confirm('Tem certeza que deseja excluir este agendamento?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="id_agendamento" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>