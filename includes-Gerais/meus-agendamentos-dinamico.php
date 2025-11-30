<?php
/**
 * Lista agendamentos do usuário
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'] ?? null;
$msg = '';

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

$agendamentos = [];
if ($id_usuario) {
    try {
        if ($is_admin) {
            $stmt = $pdo->query("
                SELECT a.*, u.nome as usuario_nome, p.nome as profissional_nome, p.especialidade
                FROM agendamentos a
                JOIN usuarios u ON a.id_usuario = u.id_usuario
                LEFT JOIN profissionais p ON a.id_nutricionista = p.id
                ORDER BY a.data_hora DESC
            ");
        } else {
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
            <table class="table mef-table" style="color: white;">
                <thead>
                    <tr style="color: white;">
                        <?php if ($is_admin): ?>
                            <th style="color: white;">Cliente</th>
                        <?php endif; ?>
                        <th style="color: white;">Profissional</th>
                        <th style="color: white;">Data</th>
                        <th style="color: white;">Horário</th>
                        <th class="text-end" style="color: white;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <?php 
                        $data_hora = new DateTime($agendamento['data_hora']);
                        $data_formatada = $data_hora->format('d/m/Y');
                        $hora_formatada = $data_hora->format('H:i');
                        ?>
                        <tr style="color: white;">
                            <?php if ($is_admin): ?>
                                <td style="color: white;"><?php echo htmlspecialchars($agendamento['usuario_nome'] ?? 'N/A'); ?></td>
                            <?php endif; ?>
                            <td style="color: white;">
                                <?php echo htmlspecialchars($agendamento['profissional_nome'] ?? 'Profissional'); ?>
                                <?php if ($agendamento['especialidade']): ?>
                                    <br><small style="color: rgba(255, 255, 255, 0.7);"><?php echo htmlspecialchars($agendamento['especialidade']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="color: white;"><?php echo $data_formatada; ?></td>
                            <td style="color: white;"><?php echo $hora_formatada; ?></td>
                            <td class="text-end" style="color: white;">
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
        <div class="modal-content" style="background: white; color: #212529;">
            <form method="POST">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-bottom: none;">
                    <h5 class="modal-title">Editar Agendamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background: white; color: #212529;">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id_agendamento" id="edit_id_agendamento">
                    
                    <div class="mb-3">
                        <label for="editarData" class="form-label" style="color: #212529 !important; font-weight: 500;">Nova Data</label>
                        <input type="date" class="form-control" name="nova_data" id="editarData" min="<?php echo date('Y-m-d'); ?>" style="background: white !important; color: #212529 !important; border: 1px solid #ced4da;" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editarHora" class="form-label" style="color: #212529 !important; font-weight: 500;">Novo Horário</label>
                        <select class="form-select" name="nova_hora" id="editarHora" style="background: white !important; color: #212529 !important; border: 1px solid #ced4da;" required>
                            <option value="" style="color: #212529;">Selecione um horário</option>
                            <option value="08:00" style="color: #212529;">08:00</option>
                            <option value="09:00" style="color: #212529;">09:00</option>
                            <option value="10:00" style="color: #212529;">10:00</option>
                            <option value="14:00" style="color: #212529;">14:00</option>
                            <option value="15:00" style="color: #212529;">15:00</option>
                            <option value="16:00" style="color: #212529;">16:00</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="background: white; border-top: 1px solid #dee2e6;">
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