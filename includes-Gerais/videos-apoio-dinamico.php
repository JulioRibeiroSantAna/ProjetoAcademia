<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin (usando o valor REAL da sessão)
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Dados dos vídeos (em um sistema real, isso viria do banco de dados)
$videos = [
    [
        'id' => 1,
        'titulo' => 'Receita saudável 1',
        'url' => 'https://www.youtube.com/embed/t6pK2j9-WI8',
        'descricao' => 'Uma receita fácil e rápida para refeições balanceadas.',
        'categoria' => 'Receitas'
    ],
    [
        'id' => 2,
        'titulo' => 'Dicas de nutrição',
        'url' => 'https://www.youtube.com/embed/ZF0MTDr0WQg',
        'descricao' => 'Conselhos simples para melhorar sua alimentação.',
        'categoria' => 'Dicas'
    ],
    [
        'id' => 3,
        'titulo' => 'Orientações para dieta',
        'url' => 'https://www.youtube.com/embed/9k_BjL6L1Qg',
        'descricao' => 'Entenda como manter uma dieta equilibrada.',
        'categoria' => 'Orientações'
    ]
];

// Definir classes de badge por categoria
$badge_classes = [
    'Receitas' => 'bg-primary',
    'Dicas' => 'bg-success',
    'Orientações' => 'bg-warning text-dark'
];
?>

<div class="mef-card p-4">
    <?php if ($is_admin): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">Vídeos de Apoio</h1>
            <p class="lead mb-0 text-muted">Administração de conteúdo</p>
        </div>
        <button class="mef-btn-primary" id="btnAddVideo">
            <i class="bi bi-plus-circle me-2"></i>Adicionar Vídeo
        </button>
    </div>
    <?php else: ?>
    <h1 class="mb-4">Vídeos de Apoio</h1>
    <?php endif; ?>
    
    <p class="lead mb-4 text-muted">
        <?php echo $is_admin ? 'Gerencie os vídeos educativos disponíveis para os usuários.' : 'Aprenda mais sobre alimentação saudável com nossos vídeos educativos.'; ?>
        Dicas, receitas e orientações para uma nutrição equilibrada!
    </p>

    <div class="row mb-4">
        <div class="col-md-8">
            <input type="search" class="mef-form-control" placeholder="Pesquisar vídeos...">
        </div>
        <div class="col-md-4">
            <select class="mef-form-control">
                <option selected>Todas as categorias</option>
                <option>Receitas</option>
                <option>Dicas</option>
                <option>Orientações</option>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($videos as $video): ?>
        <div class="col-md-6 col-lg-4">
            <div class="content-card h-100 p-3">
                <div class="ratio ratio-16x9 mb-3">
                    <iframe src="<?php echo $video['url']; ?>" title="<?php echo $video['titulo']; ?>" allowfullscreen></iframe>
                </div>
                
                <?php if ($is_admin): ?>
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4><?php echo $video['titulo']; ?></h4>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary btn-edit-video" data-video-id="<?php echo $video['id']; ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete-video" data-video-id="<?php echo $video['id']; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                <h4 class="mb-2"><?php echo $video['titulo']; ?></h4>
                <?php endif; ?>
                
                <p class="text-muted"><?php echo $video['descricao']; ?></p>
                <span class="badge <?php echo $badge_classes[$video['categoria']]; ?>"><?php echo $video['categoria']; ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Modal para adicionar/editar vídeo -->
<div class="modal fade mef-modal" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Adicionar Novo Vídeo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="videoForm">
                    <input type="hidden" id="videoId">
                    <div class="mb-3">
                        <label for="videoTitle" class="mef-form-label">Título do Vídeo</label>
                        <input type="text" class="mef-form-control" id="videoTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="videoUrl" class="mef-form-label">URL do Vídeo (YouTube)</label>
                        <input type="url" class="mef-form-control" id="videoUrl" required>
                    </div>
                    <div class="mb-3">
                        <label for="videoDescription" class="mef-form-label">Descrição</label>
                        <textarea class="mef-form-control" id="videoDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="videoCategory" class="mef-form-label">Categoria</label>
                        <select class="mef-form-control" id="videoCategory" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="Receitas">Receitas</option>
                            <option value="Dicas">Dicas</option>
                            <option value="Orientações">Orientações</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="mef-btn-primary" id="btnSaveVideo">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação para excluir -->
<div class="modal fade mef-modal" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este vídeo? Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">Excluir</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidade de pesquisa
    const searchInput = document.querySelector('input[type="search"]');
    const categorySelect = document.querySelector('select');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterVideos(searchTerm, categorySelect.value);
        });
    }
    
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            filterVideos(searchInput.value, this.value);
        });
    }
    
    function filterVideos(searchTerm, category) {
        document.querySelectorAll('.col-md-6').forEach(videoCol => {
            const title = videoCol.querySelector('h4').textContent.toLowerCase();
            const description = videoCol.querySelector('p').textContent.toLowerCase();
            const videoCategory = videoCol.querySelector('.badge').textContent;
            
            const matchesSearch = title.includes(searchTerm.toLowerCase()) || 
                                description.includes(searchTerm.toLowerCase());
            const matchesCategory = category === 'Todas as categorias' || videoCategory === category;
            
            if (matchesSearch && matchesCategory) {
                videoCol.style.display = '';
            } else {
                videoCol.style.display = 'none';
            }
        });
    }
    
    <?php if ($is_admin): ?>
    // Funcionalidades do admin
    let currentVideoId = null;
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    
    // Adicionar vídeo
    document.getElementById('btnAddVideo').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Adicionar Novo Vídeo';
        document.getElementById('videoForm').reset();
        document.getElementById('videoId').value = '';
        videoModal.show();
    });
    
    // Editar vídeo
    document.querySelectorAll('.btn-edit-video').forEach(btn => {
        btn.addEventListener('click', function() {
            const videoCard = this.closest('.content-card');
            currentVideoId = this.getAttribute('data-video-id');
            
            document.getElementById('modalTitle').textContent = 'Editar Vídeo';
            document.getElementById('videoId').value = currentVideoId;
            document.getElementById('videoTitle').value = videoCard.querySelector('h4').textContent;
            document.getElementById('videoDescription').value = videoCard.querySelector('p').textContent;
            
            const category = videoCard.querySelector('.badge').textContent;
            document.getElementById('videoCategory').value = category;
            
            const iframeSrc = videoCard.querySelector('iframe').src;
            document.getElementById('videoUrl').value = iframeSrc;
            
            videoModal.show();
        });
    });
    
    // Salvar vídeo
    document.getElementById('btnSaveVideo').addEventListener('click', function() {
        alert('Vídeo salvo com sucesso!');
        videoModal.hide();
    });
    
    // Excluir vídeo
    document.querySelectorAll('.btn-delete-video').forEach(btn => {
        btn.addEventListener('click', function() {
            currentVideoId = this.getAttribute('data-video-id');
            deleteModal.show();
        });
    });
    
    // Confirmar exclusão
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        alert(`Vídeo ${currentVideoId} excluído com sucesso!`);
        deleteModal.hide();
    });
    <?php endif; ?>
});
</script>