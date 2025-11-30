<?php
/**
 * Gerenciamento de Profissionais (CRUD - Admin)
 * Upload de foto, validação de telefone único
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = ($_SESSION['tipo_usuario'] === 'admin');
$msg = '';

$pdo->exec("CREATE TABLE IF NOT EXISTS profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    telefone VARCHAR(20) DEFAULT NULL,
    descricao TEXT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

/**
 * Upload de foto profissional (JPG, PNG, GIF, WEBP - max 5MB)
 */
function uploadFotoProfissional($file) {
    $upload_dir = __DIR__ . '/../uploads/profissionais/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erro no upload do arquivo'];
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP'];
    }
    
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Arquivo muito grande. Máximo 5MB'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'prof_' . uniqid() . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => 'uploads/profissionais/' . $filename];
    }
    
    return ['success' => false, 'message' => 'Erro ao salvar arquivo'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'adicionar') {
        $nome = trim($_POST['nome'] ?? '');
        $especialidade = trim($_POST['especialidade'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $foto = null;
        
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_result = uploadFotoProfissional($_FILES['foto']);
            if ($upload_result['success']) {
                $foto = $upload_result['filename'];
            } else {
                $msg = '❌ ' . $upload_result['message'];
            }
        }
        
        if ($nome && $especialidade && $descricao && !$msg) {
            try {
                /** Validação de email único */
                if (!empty($email)) {
                    $stmt = $pdo->prepare('SELECT id FROM profissionais WHERE email = ?');
                    $stmt->execute([$email]);
                    if ($stmt->fetch()) {
                        $msg = '❌ Este email já está cadastrado para outro profissional!';
                    }
                }
                
                /** Validação de telefone único */
                if (!empty($telefone) && !$msg) {
                    $stmt = $pdo->prepare('SELECT id FROM profissionais WHERE telefone = ?');
                    $stmt->execute([$telefone]);
                    if ($stmt->fetch()) {
                        $msg = '❌ Este telefone já está cadastrado para outro profissional!';
                    }
                }
                
                if (!$msg) {
                    $stmt = $pdo->prepare('INSERT INTO profissionais (nome, especialidade, email, telefone, descricao, foto) VALUES (?, ?, ?, ?, ?, ?)');
                    $stmt->execute([$nome, $especialidade, $email, $telefone, $descricao, $foto]);
                    $msg = '✅ Profissional adicionado com sucesso!';
                }
            } catch (PDOException $e) {
                $msg = '❌ Erro ao adicionar: ' . $e->getMessage();
            }
        } else if (!$msg) {
            $msg = '❌ Preencha todos os campos obrigatórios!';
        }
    }
    
    if ($acao === 'editar') {
        $id = $_POST['id'] ?? 0;
        $nome = trim($_POST['nome'] ?? '');
        $especialidade = trim($_POST['especialidade'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        
        if ($id && $nome && $especialidade && $descricao) {
            try {
                // Buscar foto atual
                $stmt = $pdo->prepare('SELECT foto FROM profissionais WHERE id = ?');
                $stmt->execute([$id]);
                $prof_atual = $stmt->fetch();
                $foto = $prof_atual['foto'];
                
                // Verificar se o telefone já existe em outro profissional
                if (!empty($telefone)) {
                    $stmt = $pdo->prepare('SELECT id FROM profissionais WHERE telefone = ? AND id != ?');
                    $stmt->execute([$telefone, $id]);
                    if ($stmt->fetch()) {
                        $msg = '❌ Este telefone já está cadastrado para outro profissional!';
                    }
                }
                
                // Processar novo upload de foto
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE && !$msg) {
                    $upload_result = uploadFotoProfissional($_FILES['foto']);
                    if ($upload_result['success']) {
                        // Remover foto antiga
                        if ($foto && file_exists(__DIR__ . '/../' . $foto)) {
                            unlink(__DIR__ . '/../' . $foto);
                        }
                        $foto = $upload_result['filename'];
                    } else {
                        $msg = '❌ ' . $upload_result['message'];
                    }
                }
                
                if (!$msg) {
                    $stmt = $pdo->prepare('UPDATE profissionais SET nome = ?, especialidade = ?, email = ?, telefone = ?, descricao = ?, foto = ? WHERE id = ?');
                    $stmt->execute([$nome, $especialidade, $email, $telefone, $descricao, $foto, $id]);
                    $msg = '✅ Profissional atualizado com sucesso!';
                }
            } catch (PDOException $e) {
                $msg = '❌ Erro ao atualizar: ' . $e->getMessage();
            }
        }
    }
    
    if ($acao === 'excluir') {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            try {
                // Buscar e remover foto
                $stmt = $pdo->prepare('SELECT foto FROM profissionais WHERE id = ?');
                $stmt->execute([$id]);
                $prof = $stmt->fetch();
                if ($prof && $prof['foto'] && file_exists(__DIR__ . '/../' . $prof['foto'])) {
                    unlink(__DIR__ . '/../' . $prof['foto']);
                }
                
                $stmt = $pdo->prepare('DELETE FROM profissionais WHERE id = ?');
                $stmt->execute([$id]);
                $msg = '✅ Profissional excluído com sucesso!';
            } catch (PDOException $e) {
                $msg = '❌ Erro ao excluir: ' . $e->getMessage();
            }
        }
    }
}

// Buscar profissionais
try {
    $stmt = $pdo->query('SELECT * FROM profissionais ORDER BY nome');
    $profissionais = $stmt->fetchAll();
} catch (PDOException $e) {
    $profissionais = [];
}
?>

<!-- CSS para tema escuro -->
<style>
/* ========== TEMA ESCURO PARA GERENCIAMENTO ========== */
.mef-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #e4e4e4;
}

.mef-card h1,
.mef-card h2,
.mef-card h3,
.mef-card h4,
.mef-card h5 {
    color: #ffffff;
}

/* Tabela Escura */
.table {
    color: #e4e4e4;
    border-color: #2d3748;
}

.table thead th {
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-color: #2d3748;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table tbody tr {
    border-color: #2d3748;
}

/* Desabilitar hover na tabela */
.table tbody tr:hover {
    background-color: transparent !important;
    color: #e4e4e4 !important;
}

.table tbody tr:hover td {
    background-color: transparent !important;
    color: #e4e4e4 !important;
}

.table tbody td {
    border-color: #2d3748;
    vertical-align: middle;
    color: #e4e4e4;
}

/* Botões */
.btn-success {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-danger {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
}

/* Modais Escuros */
.modal-content {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #e4e4e4;
    border: 1px solid #2d3748;
}

.modal-header {
    border-bottom: 1px solid #2d3748;
}

.modal-title {
    color: #ffffff;
}

.modal-footer {
    border-top: 1px solid #2d3748;
}

.btn-close {
    filter: invert(1);
}

/* Inputs Escuros */
.form-control,
.form-select,
textarea.form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid #2d3748;
    color: #e4e4e4;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus,
textarea.form-control:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: #667eea;
    color: #ffffff;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control::placeholder {
    color: #a0a0a0;
}

.form-label {
    color: #ffffff;
    font-weight: 500;
}

/* Alert Escuro */
.alert-info {
    background: rgba(102, 126, 234, 0.2);
    border: 1px solid #667eea;
    color: #ffffff;
}

.alert-success {
    background: rgba(72, 187, 120, 0.2);
    border: 1px solid #48bb78;
    color: #ffffff;
}

/* Ícone vazio */
.bi-inbox {
    color: rgba(255, 255, 255, 0.3);
}

.text-muted {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Responsividade */
@media (max-width: 768px) {
    .table {
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>

<div class="mef-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gerenciar Profissionais</h1>
        <?php if ($is_admin): ?>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle"></i> Adicionar Profissional
            </button>
        <?php endif; ?>
    </div>
    
    <?php if ($msg): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="80">Foto</th>
                    <th>Nome</th>
                    <th>Especialidade</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <?php if ($is_admin): ?>
                        <th width="150">Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($profissionais)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2 text-muted">Nenhum profissional cadastrado</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($profissionais as $prof): ?>
                        <tr>
                            <td>
                                <?php if ($prof['foto']): ?>
                                    <img src="<?php echo htmlspecialchars('../' . $prof['foto']); ?>" 
                                         alt="Foto de <?php echo htmlspecialchars($prof['nome']); ?>" 
                                         class="rounded-circle" 
                                         style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #667eea;">
                                <?php else: ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="bi bi-person-fill" style="font-size: 1.5rem; color: white;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($prof['nome']); ?></td>
                            <td><?php echo htmlspecialchars($prof['especialidade']); ?></td>
                            <td><?php echo htmlspecialchars($prof['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($prof['telefone'] ?? '-'); ?></td>
                            <?php if ($is_admin): ?>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editarProfissional(<?php echo $prof['id']; ?>, '<?php echo addslashes($prof['nome']); ?>', '<?php echo addslashes($prof['especialidade']); ?>', '<?php echo addslashes($prof['email'] ?? ''); ?>', '<?php echo addslashes($prof['telefone'] ?? ''); ?>', '<?php echo addslashes($prof['descricao']); ?>', '<?php echo addslashes($prof['foto'] ?? ''); ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="excluir(<?php echo $prof['id']; ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Modal para adicionar profissional -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="adicionar">
                    
                    <div class="mb-3">
                        <label class="form-label">Foto do Profissional</label>
                        <input type="file" class="form-control" name="foto" accept="image/*" onchange="previewImage(this, 'preview_add')">
                        <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WEBP (máx. 5MB)</small>
                        <div class="mt-2">
                            <img id="preview_add" style="max-width: 150px; max-height: 150px; display: none; border-radius: 10px; border: 2px solid #667eea;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nome *</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Especialidade *</label>
                        <select class="form-select" name="especialidade" required>
                            <option value="">Selecione a especialidade</option>
                            <option value="Nutricionista">Nutricionista</option>
                            <option value="Médico Endocrinologista">Médico Endocrinologista</option>
                            <option value="Educador Físico">Educador Físico</option>
                            <option value="Psicólogo">Psicólogo</option>
                            <option value="Fisioterapeuta">Fisioterapeuta</option>
                            <option value="Médico Cardiologista">Médico Cardiologista</option>
                            <option value="Enfermeiro">Enfermeiro</option>
                            <option value="Terapeuta Ocupacional">Terapeuta Ocupacional</option>
                            <option value="Fonoaudiólogo">Fonoaudiólogo</option>
                            <option value="Médico Clínico Geral">Médico Clínico Geral</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-control" name="telefone" placeholder="(51) 99999-9999" maxlength="15">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição *</label>
                        <textarea class="form-control" name="descricao" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar profissional -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Foto Atual</label>
                        <div id="current_photo_container" class="mb-2"></div>
                        <label class="form-label">Nova Foto (deixe em branco para manter a atual)</label>
                        <input type="file" class="form-control" name="foto" accept="image/*" onchange="previewImage(this, 'preview_edit')">
                        <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WEBP (máx. 5MB)</small>
                        <div class="mt-2">
                            <img id="preview_edit" style="max-width: 150px; max-height: 150px; display: none; border-radius: 10px; border: 2px solid #667eea;">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nome *</label>
                        <input type="text" class="form-control" name="nome" id="edit_nome" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Especialidade *</label>
                        <select class="form-select" name="especialidade" id="edit_especialidade" required>
                            <option value="">Selecione a especialidade</option>
                            <option value="Nutricionista">Nutricionista</option>
                            <option value="Médico Endocrinologista">Médico Endocrinologista</option>
                            <option value="Educador Físico">Educador Físico</option>
                            <option value="Psicólogo">Psicólogo</option>
                            <option value="Fisioterapeuta">Fisioterapeuta</option>
                            <option value="Médico Cardiologista">Médico Cardiologista</option>
                            <option value="Enfermeiro">Enfermeiro</option>
                            <option value="Terapeuta Ocupacional">Terapeuta Ocupacional</option>
                            <option value="Fonoaudiólogo">Fonoaudiólogo</option>
                            <option value="Médico Clínico Geral">Médico Clínico Geral</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="tel" class="form-control" name="telefone" id="edit_telefone" placeholder="(51) 99999-9999" maxlength="15">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição *</label>
                        <textarea class="form-control" name="descricao" id="edit_descricao" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Definir variáveis globais
const IS_ADMIN = <?php echo $is_admin ? 'true' : 'false'; ?>;

// Função para máscara de telefone
function formatarTelefone(input) {
    let valor = input.value.replace(/\D/g, '');
    
    // Se começar com 51, manter
    if (valor.length > 0) {
        if (valor.length <= 2) {
            valor = '(' + valor;
        } else if (valor.length <= 6) {
            valor = '(' + valor.substring(0, 2) + ') ' + valor.substring(2);
        } else if (valor.length <= 10) {
            valor = '(' + valor.substring(0, 2) + ') ' + valor.substring(2, 6) + '-' + valor.substring(6);
        } else {
            valor = '(' + valor.substring(0, 2) + ') ' + valor.substring(2, 7) + '-' + valor.substring(7, 11);
        }
    }
    
    input.value = valor;
}

// Adicionar evento aos campos de telefone
document.addEventListener('DOMContentLoaded', function() {
    const telefoneInputs = document.querySelectorAll('input[name="telefone"]');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatarTelefone(this);
        });
        
        // Se já tiver valor, formatar
        if (input.value) {
            formatarTelefone(input);
        }
    });
});

// Função para preview de imagem
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Função para excluir profissional
function excluir(id) {
    if (confirm('Tem certeza que deseja excluir este profissional?\n\nEsta ação NÃO pode ser desfeita!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Função para editar profissional
function editarProfissional(id, nome, especialidade, email, telefone, descricao, foto) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_especialidade').value = especialidade;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_telefone').value = telefone;
    document.getElementById('edit_descricao').value = descricao;
    
    // Mostrar foto atual
    const currentPhotoContainer = document.getElementById('current_photo_container');
    if (foto) {
        currentPhotoContainer.innerHTML = `
            <img src="../${foto}" alt="Foto atual" style="max-width: 150px; max-height: 150px; border-radius: 10px; border: 2px solid #667eea;">
        `;
    } else {
        currentPhotoContainer.innerHTML = `
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="bi bi-person-fill" style="font-size: 2rem; color: white;"></i>
            </div>
            <p class="text-muted mt-2">Nenhuma foto cadastrada</p>
        `;
    }
    
    // Limpar preview
    document.getElementById('preview_edit').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>
<?php endif; ?>
