<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vídeos de Apoio - MEF</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    /* Estilos específicos para esta página */
    .videos-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    
    .content-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    
    /* Melhorias para os cards de vídeo */
    .video-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .video-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>
  <?php include 'includes-UsuarioLogado/navbar-UsuarioLogado.php'; ?>

  <!-- Conteúdo principal com container flexível -->
  <main class="container mt-5 pt-4 videos-container">
    <div class="gradient-card p-4 content-wrapper">
      <h1 class="mb-4">Vídeos de Apoio</h1>
      
      <p class="lead mb-4">
        Aprenda mais sobre alimentação saudável com nossos vídeos educativos. Dicas, receitas e orientações para uma nutrição equilibrada!
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
          <div class="gradient-card h-100 p-3 video-card">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/t6pK2j9-WI8" title="Receita saudável" allowfullscreen></iframe>
            </div>
            <h4>Receita saudável 1</h4>
            <p>Uma receita fácil e rápida para refeições balanceadas.</p>
            <span class="badge bg-primary">Receitas</span>
          </div>
        </div>
        
        <!-- Vídeo 2 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3 video-card">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/ZF0MTDr0WQg" title="Dicas de nutrição" allowfullscreen></iframe>
            </div>
            <h4>Dicas de nutrição</h4>
            <p>Conselhos simples para melhorar sua alimentação.</p>
            <span class="badge bg-success">Dicas</span>
          </div>
        </div>
        
        <!-- Vídeo 3 -->
        <div class="col-md-6 col-lg-4">
          <div class="gradient-card h-100 p-3 video-card">
            <div class="ratio ratio-16x9 mb-3">
              <iframe src="https://www.youtube.com/embed/9k_BjL6L1Qg" title="Orientações para dieta" allowfullscreen></iframe>
            </div>
            <h4>Orientações para dieta</h4>
            <p>Entenda como manter uma dieta equilibrada.</p>
            <span class="badge bg-warning text-dark">Orientações</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Aqui você pode adicionar a lógica para filtrar os vídeos
    document.querySelector('input[type="search"]').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      document.querySelectorAll('.col-md-6').forEach(videoCol => {
        const title = videoCol.querySelector('h4').textContent.toLowerCase();
        const description = videoCol.querySelector('p').textContent.toLowerCase();
        if (title.includes(searchTerm) || description.includes(searchTerm)) {
          videoCol.style.display = '';
        } else {
          videoCol.style.display = 'none';
        }
      });
    });

    // Filtro por categoria
    document.querySelector('select').addEventListener('change', function() {
      const category = this.value;
      document.querySelectorAll('.col-md-6').forEach(videoCol => {
        const badge = videoCol.querySelector('.badge').textContent;
        if (category === 'Todas as categorias' || badge === category) {
          videoCol.style.display = '';
        } else {
          videoCol.style.display = 'none';
        }
      });
    });
  </script>
  <script src="../js/videos.js" type="module"></script>
  <script type="module" src="../js/main.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>