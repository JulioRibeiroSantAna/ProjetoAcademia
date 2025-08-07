<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vídeos de Apoio - Admin - MEF</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="logado-Adm.php">MEF</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logado-Adm.php#fale-conosco">Fale Conosco</a>
          </li>
        </ul>

        <div class="dropdown ms-3">
          <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> Menu
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="videos-apoio-Adm.php">Vídeos de Apoio</a></li>
            <li class="dropdown-submenu">
              <a class="dropdown-item d-flex justify-content-between align-items-center submenu-toggle" 
                 href="#" 
                 role="button">
                Profissionais <i class="bi bi-chevron-down small"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="agendamento-Adm.php">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="bate-papo-Adm.php">Bate-Papo</a></li>
                <li><a class="dropdown-item" href="meus-agendamentos-Adm.php">Meus Agendamentos</a></li>
              </ul>
            </li>
            <li><a class="dropdown-item" href="perfil-Adm.php">Perfil de Usuário</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../index.php">Sair</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Conteúdo principal -->
  <main class="container mt-5 pt-5">
    <div class="gradient-card p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="mb-0">Vídeos de Apoio</h1>
          <p class="lead mb-0">Administração de conteúdo</p>
        </div>
        <button class="btn btn-primary" id="btnAddVideo">
          <i class="bi bi-plus-circle me-2"></i>Adicionar Vídeo
        </button>
      </div>

      <p class="mb-4">
        Gerencie os vídeos educativos disponíveis para os usuários. Dicas, receitas e orientações para uma nutrição equilibrada!
      </p>

      <div class="row mb-4">
        <div class="col-md-8">
          <input type="search" class="form-control" placeholder="Pesquisar vídeos...">
        </div>
        <div class="col-md-4">
          <select class="form-select">
            <option selected>Todas as categorias</option>
            <option>Receitas</option>
            <option>Dicas</option>
            <option>Orientações</option>
          </select>
        </div>
      </div>

      <div class="row g-4">
        <!-- Vídeo 1 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/t6pK2j9-WI8" title="Receita saudável" allowfullscreen></iframe>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h4>Receita saudável 1</h4>
              <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary btn-edit-video" data-video-id="1">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-delete-video">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
            <p>Uma receita fácil e rápida para refeições balanceadas.</p>
            <span class="badge bg-primary">Receitas</span>
          </div>
        </div>
        
        <!-- Vídeo 2 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/ZF0MTDr0WQg" title="Dicas de nutrição" allowfullscreen></iframe>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h4>Dicas de nutrição</h4>
              <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary btn-edit-video" data-video-id="2">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-delete-video">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
            <p>Conselhos simples para melhorar sua alimentação.</p>
            <span class="badge bg-success">Dicas</span>
          </div>
        </div>
        
        <!-- Vídeo 3 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/9k_BjL6L1Qg" title="Orientações para dieta" allowfullscreen></iframe>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h4>Orientações para dieta</h4>
              <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary btn-edit-video" data-video-id="3">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger btn-delete-video">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
            <p>Entenda como manter uma dieta equilibrada.</p>
            <span class="badge bg-warning text-dark">Orientações</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal para adicionar/editar vídeo -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
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
              <label for="videoTitle" class="form-label">Título do Vídeo</label>
              <input type="text" class="form-control" id="videoTitle" required>
            </div>
            <div class="mb-3">
              <label for="videoUrl" class="form-label">URL do Vídeo (YouTube)</label>
              <input type="url" class="form-control" id="videoUrl" required>
            </div>
            <div class="mb-3">
              <label for="videoDescription" class="form-label">Descrição</label>
              <textarea class="form-control" id="videoDescription" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="videoCategory" class="form-label">Categoria</label>
              <select class="form-select" id="videoCategory" required>
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
          <button type="button" class="btn btn-primary" id="btnSaveVideo">Salvar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de confirmação para excluir -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Variáveis globais
    let currentVideoId = null;
    const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    
    // Botão para adicionar novo vídeo
    document.getElementById('btnAddVideo').addEventListener('click', function() {
      document.getElementById('modalTitle').textContent = 'Adicionar Novo Vídeo';
      document.getElementById('videoForm').reset();
      document.getElementById('videoId').value = '';
      videoModal.show();
    });
    
    // Botões para editar vídeo
    document.querySelectorAll('.btn-edit-video').forEach(btn => {
      btn.addEventListener('click', function() {
        const videoCard = this.closest('.gradient-card');
        currentVideoId = this.getAttribute('data-video-id');
        
        // Preencher o formulário com os dados atuais (simulação)
        document.getElementById('modalTitle').textContent = 'Editar Vídeo';
        document.getElementById('videoId').value = currentVideoId;
        document.getElementById('videoTitle').value = videoCard.querySelector('h4').textContent;
        document.getElementById('videoDescription').value = videoCard.querySelector('p').textContent;
        
        // Extrair a categoria do badge
        const category = videoCard.querySelector('.badge').textContent;
        document.getElementById('videoCategory').value = category;
        
        // Extrair URL do iframe (simplificado)
        const iframeSrc = videoCard.querySelector('iframe').src;
        document.getElementById('videoUrl').value = iframeSrc;
        
        videoModal.show();
      });
    });
    
    // Botão para salvar vídeo (adicionar/editar)
    document.getElementById('btnSaveVideo').addEventListener('click', function() {
      // Aqui você implementaria a lógica para salvar no banco de dados
      alert('Vídeo salvo com sucesso!');
      videoModal.hide();
    });
    
    // Botões para excluir vídeo
    document.querySelectorAll('.btn-delete-video').forEach(btn => {
      btn.addEventListener('click', function() {
        currentVideoId = this.closest('.gradient-card').querySelector('.btn-edit-video').getAttribute('data-video-id');
        deleteModal.show();
      });
    });
    
    // Confirmação de exclusão
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
      // Aqui você implementaria a lógica para excluir do banco de dados
      alert(`Vídeo ${currentVideoId} excluído com sucesso!`);
      deleteModal.hide();
    });
    
    // Filtro de pesquisa
    document.querySelector('input[type="search"]').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      document.querySelectorAll('.gradient-card').forEach(card => {
        const title = card.querySelector('h4').textContent.toLowerCase();
        const description = card.querySelector('p').textContent.toLowerCase();
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          card.closest('.col-md-6').style.display = '';
        } else {
          card.closest('.col-md-6').style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>