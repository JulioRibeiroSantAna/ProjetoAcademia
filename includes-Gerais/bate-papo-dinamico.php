<?php
// includes-Gerais/bate-papo-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Dados dos profissionais
$profissionais = [
    [
        'id' => 'gabriel',
        'nome' => 'GABRIEL DA VILA',
        'especialidade' => 'Nutricionista',
        'descricao' => 'Especialista em nutrição esportiva com 10 anos de experiência.',
        'foto' => '../imagens-teste/goku.jpg',
        'horarios' => [
            'Segunda: 08:00 - 12:00',
            'Quarta: 14:00 - 18:00',
            'Sexta: 09:00 - 13:00'
        ]
    ],
    [
        'id' => 'carlos',
        'nome' => 'DR. CARLOS',
        'especialidade' => 'Psicólogo',
        'descricao' => 'Especialista em terapia cognitivo-comportamental.',
        'foto' => '../imagens-teste/goku.jpg',
        'horarios' => [
            'Terça: 09:00 - 13:00',
            'Quinta: 15:00 - 19:00',
            'Sábado: 10:00 - 14:00'
        ]
    ]
];

// Definir classes de badge por especialidade
$badge_classes = [
    'Nutricionista' => 'bg-primary',
    'Psicólogo' => 'bg-success',
    'Médico' => 'bg-info',
    'Educador Físico' => 'bg-warning text-dark'
];
?>

<div class="mef-card">
    <?php if ($is_admin): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">BATE-PAPO</h1>
            <p class="lead mb-0">Escolha um profissional para conversar</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProfessionalModal">
            <i class="bi bi-plus-lg"></i> Adicionar Profissional
        </button>
    </div>
    <?php else: ?>
    <h1 class="mb-4">BATE-PAPO</h1>
    <p class="lead mb-4">Escolha um profissional para conversar</p>
    <?php endif; ?>

    <div class="symmetric-grid">
        <?php foreach ($profissionais as $profissional): ?>
        <div class="scale-in">
            <div class="professional-card">
                <div class="professional-image-container">
                    <img src="<?php echo $profissional['foto']; ?>" class="card-img-top" alt="<?php echo $profissional['nome']; ?>">
                </div>
                <div class="card-body">
                    <?php if ($is_admin): ?>
                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editProfessionalModal" data-profissional='<?php echo json_encode($profissional); ?>'>
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo $profissional['id']; ?>')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <h5 class="card-title"><?php echo $profissional['nome']; ?></h5>
                    <span class="badge <?php echo $badge_classes[$profissional['especialidade']]; ?>"><?php echo $profissional['especialidade']; ?></span>
                    <p class="card-text"><?php echo $profissional['descricao']; ?></p>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo $is_admin ? '../AdmLogado/conversa-Adm.php' : '../UsuarioLogado/conversa.php'; ?>?prof=<?php echo $profissional['id']; ?>" class="btn mef-btn-primary flex-grow-1 me-2">
                            <i class="bi bi-chat-left-text"></i> Conversar
                        </a>
                        
                        <?php if ($is_admin && !empty($profissional['horarios'])): ?>
                        <button class="btn btn-outline-light" data-bs-toggle="collapse" data-bs-target="#horarios<?php echo ucfirst($profissional['id']); ?>">
                            <i class="bi bi-calendar3"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($is_admin && !empty($profissional['horarios'])): ?>
                    <!-- Horários (collapse) -->
                    <div class="collapse mt-3" id="horarios<?php echo ucfirst($profissional['id']); ?>">
                        <div class="card card-body bg-dark">
                            <h6 class="mb-2">Horários de Atendimento:</h6>
                            <ul class="mb-0">
                                <?php foreach ($profissional['horarios'] as $horario): ?>
                                <li><?php echo $horario; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Modal de Adicionar Profissional -->
<div class="modal fade mef-modal" id="addProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Adicionar Profissional</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProfessionalForm">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img id="photoPreviewAdd" src="https://via.placeholder.com/200" class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;" alt="Preview da Foto">
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-light" onclick="document.getElementById('photoUploadAdd').click()">
                                    <i class="bi bi-camera-fill"></i> Alterar Foto
                                </button>
                                <input type="file" id="photoUploadAdd" accept="image/*" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="mef-form-label">Nome Completo</label>
                                <input type="text" class="form-control mef-form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="mef-form-label">Especialidade</label>
                                <select class="form-select mef-form-control" required>
                                    <option value="">Selecione uma especialidade</option>
                                    <option value="Nutricionista">Nutricionista</option>
                                    <option value="Psicólogo">Psicólogo</option>
                                    <option value="Médico">Médico</option>
                                    <option value="Educador Físico">Educador Físico</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="mef-form-label">Descrição</label>
                                <textarea class="form-control mef-form-control" rows="3" placeholder="Breve descrição sobre o profissional" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="mb-3">Horários de Atendimento</h5>
                        
                        <?php 
                        $dias_semana = [
                            'Segunda-feira' => 'monday',
                            'Terça-feira' => 'tuesday', 
                            'Quarta-feira' => 'wednesday',
                            'Quinta-feira' => 'thursday',
                            'Sexta-feira' => 'friday',
                            'Sábado' => 'saturday'
                        ];
                        
                        foreach ($dias_semana as $dia_nome => $dia_id): ?>
                        <div class="schedule-day mb-3">
                            <div class="form-check">
                                <input class="form-check-input day-checkbox" type="checkbox" id="<?php echo $dia_id; ?>CheckAdd">
                                <label class="form-check-label" for="<?php echo $dia_id; ?>CheckAdd"><?php echo $dia_nome; ?></label>
                            </div>
                            <div id="<?php echo $dia_id; ?>TimesAdd" class="mt-2"></div>
                            <button type="button" class="btn btn-sm btn-outline-light add-time-btn mt-2" data-day="<?php echo $dia_id; ?>Add">
                                <i class="bi bi-plus"></i> Adicionar horário
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success">Adicionar Profissional</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Profissional -->
<div class="modal fade mef-modal" id="editProfessionalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Profissional</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfessionalForm">
                    <input type="hidden" id="editProfessionalId">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img id="photoPreviewEdit" src="https://via.placeholder.com/200" class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;" alt="Preview da Foto">
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-light" onclick="document.getElementById('photoUploadEdit').click()">
                                    <i class="bi bi-camera-fill"></i> Alterar Foto
                                </button>
                                <input type="file" id="photoUploadEdit" accept="image/*" class="d-none">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="mef-form-label">Nome Completo</label>
                                <input type="text" class="form-control mef-form-control" id="editNome" required>
                            </div>
                            <div class="mb-3">
                                <label class="mef-form-label">Especialidade</label>
                                <select class="form-select mef-form-control" id="editEspecialidade" required>
                                    <option value="Nutricionista">Nutricionista</option>
                                    <option value="Psicólogo">Psicólogo</option>
                                    <option value="Médico">Médico</option>
                                    <option value="Educador Físico">Educador Físico</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="mef-form-label">Descrição</label>
                                <textarea class="form-control mef-form-control" id="editDescricao" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5 class="mb-3">Horários de Atendimento</h5>
                        
                        <?php foreach ($dias_semana as $dia_nome => $dia_id): ?>
                        <div class="schedule-day mb-3">
                            <div class="form-check">
                                <input class="form-check-input day-checkbox" type="checkbox" id="<?php echo $dia_id; ?>CheckEdit">
                                <label class="form-check-label" for="<?php echo $dia_id; ?>CheckEdit"><?php echo $dia_nome; ?></label>
                            </div>
                            <div id="<?php echo $dia_id; ?>TimesEdit" class="mt-2"></div>
                            <button type="button" class="btn btn-sm btn-outline-light add-time-btn mt-2" data-day="<?php echo $dia_id; ?>Edit">
                                <i class="bi bi-plus"></i> Adicionar horário
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveEditProfessional">Salvar Alterações</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmação de Exclusão -->
<div class="modal fade mef-modal" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este profissional?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== FUNCIONALIDADES DO ADMIN ==========
    
    // Função para confirmar exclusão
    function confirmDelete(professionalId) {
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        document.getElementById('confirmDeleteBtn').onclick = function() {
            console.log('Profissional excluído:', professionalId);
            alert('Profissional excluído com sucesso!');
            deleteModal.hide();
        };
        deleteModal.show();
    }

    // Função para preview da foto
    function setupPhotoPreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Função para adicionar campos de horário
    function setupTimeAddButtons() {
        document.querySelectorAll('.add-time-btn').forEach(button => {
            button.addEventListener('click', function() {
                const day = this.getAttribute('data-day');
                const dayId = day.replace('Add', '').replace('Edit', '');
                const timesContainer = document.getElementById(`${dayId}Times${day.includes('Add') ? 'Add' : 'Edit'}`);
                
                const timeGroup = document.createElement('div');
                timeGroup.className = 'time-input-group mb-2';
                timeGroup.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <input type="time" class="form-control form-control-sm" placeholder="Início">
                        <span>às</span>
                        <input type="time" class="form-control form-control-sm" placeholder="Fim">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-time-btn">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                
                timesContainer.appendChild(timeGroup);
                
                // Adiciona evento para remover o horário
                timeGroup.querySelector('.remove-time-btn').addEventListener('click', function() {
                    timeGroup.remove();
                });
            });
        });
    }

    // Configuração dos modais de edição
    const editModal = document.getElementById('editProfessionalModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const profissional = JSON.parse(button.getAttribute('data-profissional'));
            
            // Preencher os campos do formulário
            document.getElementById('editProfessionalId').value = profissional.id;
            document.getElementById('editNome').value = profissional.nome;
            document.getElementById('editEspecialidade').value = profissional.especialidade;
            document.getElementById('editDescricao').value = profissional.descricao;
            document.getElementById('photoPreviewEdit').src = profissional.foto;
            
            // Limpar horários existentes
            <?php foreach ($dias_semana as $dia_nome => $dia_id): ?>
            document.getElementById('<?php echo $dia_id; ?>TimesEdit').innerHTML = '';
            document.getElementById('<?php echo $dia_id; ?>CheckEdit').checked = false;
            <?php endforeach; ?>
            
            // Adicionar horários (simulação)
            if (profissional.id === 'gabriel') {
                document.getElementById('mondayCheckEdit').checked = true;
                const mondayContainer = document.getElementById('mondayTimesEdit');
                
                const timeGroup1 = document.createElement('div');
                timeGroup1.className = 'time-input-group mb-2';
                timeGroup1.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <input type="time" class="form-control form-control-sm" value="08:00">
                        <span>às</span>
                        <input type="time" class="form-control form-control-sm" value="12:00">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-time-btn">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
                mondayContainer.appendChild(timeGroup1);
            }
            
            // Reconfigurar botões de remover
            document.querySelectorAll('.remove-time-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.time-input-group').remove();
                });
            });
        });
    }

    // Configura preview de fotos
    setupPhotoPreview('photoUploadAdd', 'photoPreviewAdd');
    setupPhotoPreview('photoUploadEdit', 'photoPreviewEdit');
    
    // Configura botões de adicionar horário
    setupTimeAddButtons();
    
    // Salvar edição
    document.getElementById('saveEditProfessional').addEventListener('click', function() {
        alert('Profissional atualizado com sucesso!');
        bootstrap.Modal.getInstance(document.getElementById('editProfessionalModal')).hide();
    });
});
</script>
<?php endif; ?>