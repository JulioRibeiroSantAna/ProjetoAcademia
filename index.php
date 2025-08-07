<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEF</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#home">MEF</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#profissionais">Profissionais</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#fale-conosco">Fale Conosco</a>
          </li>
        </ul>

        <a href="Autenticacao/login.php" class="btn btn-light">
          <i class="bi bi-person-circle me-1"></i> Entrar
        </a>
      </div>
    </div>
  </nav>

  <!-- Conteúdo Principal -->
  <main>
    <!-- Seção Hero -->
    <section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">PREPARE-SE PARA MUDAR</h1>
        <h2 class="h3 mb-5">PARA MELHOR.</h2>
        <div class="d-flex justify-content-center gap-3">
          <a href="Autenticacao/login.php" class="btn btn-primary btn-lg">Entrar na Plataforma</a>
          <a href="#sobre" class="btn btn-outline-light btn-lg">Saiba Mais</a>
        </div>
      </div>
    </section>

    <!-- Sobre -->
    <section id="sobre" class="gradient-card py-5">
      <div class="container">
        <h2 class="text-center mb-5">SOBRE NOSSA PLATAFORMA</h2>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <p class="lead text-center">Bem-vindo ao MEF - sua plataforma de saúde e bem-estar!</p>
            <p>Aqui, você encontra tudo o que precisa para melhorar sua saúde e bem-estar! Nosso sistema permite que você agende consultas com profissionais da área, tire dúvidas sobre nutrição e tenha acesso a conteúdos exclusivos sobre alimentação saudável e atividades físicas.</p>
            <p>Descubra recomendações de alimentos, receitas nutritivas e vídeos explicativos sobre exercícios físicos. Se ainda não tem cadastro, você pode explorar algumas dicas e vídeos, mas para aproveitar todos os benefícios e interagir com nossos especialistas, basta se cadastrar!</p>
            <p class="text-center mt-4"><strong>Cuide da sua saúde de forma prática e acessível com o MEF!</strong></p>
          </div>
        </div>
      </div>
    </section>

    <!-- Profissionais -->
    <section id="profissionais" class="gradient-card py-5">
      <div class="container">
        <h2 class="text-center mb-5">NOSSOS PROFISSIONAIS</h2>
        <p class="text-center lead mb-5">Cada corpo é diferente, por isso nos certificamos de que você possa escolher um plano que funcione melhor para você.</p>

        <div class="row g-4">
          <!-- Profissional 1 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gabriel da Vila">
              <div class="card-body text-center">
                <h5 class="card-title">Gabriel da Vila</h5>
                <p class="card-text text-muted">Nutricionista Esportivo</p>
                <a href="Autenticacao/login.php" class="btn btn-sm btn-primary">Conhecer</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 2 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gustavo Silva">
              <div class="card-body text-center">
                <h5 class="card-title">Gustavo Silva</h5>
                <p class="card-text text-muted">Personal Trainer</p>
                <a href="Autenticacao/login.php" class="btn btn-sm btn-primary">Conhecer</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 3 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Julio Ribeiro">
              <div class="card-body text-center">
                <h5 class="card-title">Julio Ribeiro</h5>
                <p class="card-text text-muted">Endocrinologista</p>
                <a href="Autenticacao/login.php" class="btn btn-sm btn-primary">Conhecer</a>
              </div>
            </div>
          </div>
          
          <!-- Profissional 4 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Miqueias">
              <div class="card-body text-center">
                <h5 class="card-title">Miqueias</h5>
                <p class="card-text text-muted">Psicólogo</p>
                <a href="Autenticacao/login.php" class="btn btn-sm btn-primary">Conhecer</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Fale Conosco -->
    <section id="fale-conosco" class="gradient-card py-5">
      <div class="container text-center">
        <h2 class="mb-4">FALE CONOSCO</h2>
        <p class="lead mb-4">Siga a MEF nas redes sociais ou entre em contato</p>
        
        <div class="d-flex justify-content-center gap-3 mb-4">
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-instagram fs-4"></i>
          </a>
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-facebook fs-4"></i>
          </a>
          <a href="#" class="btn btn-dark rounded-circle p-3">
            <i class="bi bi-whatsapp fs-4"></i>
          </a>
        </div>
        
        <p class="mb-4">
          <i class="bi bi-envelope me-2"></i>
          <a href="mailto:julioribeiro041@gmail.com" class="text-white">julioribeiro041@gmail.com</a>
        </p>
        
        <a href="#" class="btn btn-primary px-4 py-2">
          <i class="bi bi-headset me-2"></i> Contato Rápido
        </a>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <footer class="gradient-card text-white py-4">
    <div class="container text-center">
      <p class="mb-0">© 2023 MEF - Todos os direitos de Juliusss</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module" src="js/main.js"></script>
</body>
</html>