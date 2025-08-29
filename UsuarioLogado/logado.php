<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área Logada - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <?php include 'includes-UsuarioLogado/navbar-UsuarioLogado.php'; ?>

  <main>
    <!-- Seção Hero -->
    <section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
      <div class="container">
        <h1 class="display-4 fw-bold mb-4">Bem-vindo(a) ao MEF</h1>
        <h2 class="h3 mb-5">Área do Usuário</h2>
        <div class="d-flex justify-content-center gap-3">
          <a href="agendamento.php" class="btn btn-primary btn-lg">Agendar Consulta</a>
          <a href="bate-papo.php" class="btn btn-outline-light btn-lg">Bate-Papo</a>
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
            <p>Aqui você encontra tudo o que precisa para melhorar sua saúde e qualidade de vida. Nosso sistema permite que você:</p>
            <ul class="mb-4">
              <li>Agende consultas com profissionais qualificados</li>
              <li>Tire dúvidas sobre nutrição e saúde</li>
              <li>Acesse conteúdos exclusivos sobre alimentação saudável</li>
              <li>Receba orientações personalizadas</li>
            </ul>
            <p>Descubra recomendações de alimentos, receitas nutritivas e vídeos explicativos sobre exercícios físicos.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Profissionais -->
    <section id="profissionais" class="gradient-card py-5">
      <div class="container">
        <h2 class="text-center mb-5">NOSSOS PROFISSIONAIS</h2>
        <p class="text-center lead mb-5">Conheça nossa equipe de especialistas</p>

        <div class="row g-4">
          <!-- Profissional 1 -->
          <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm">
              <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gabriel da Vila">
              <div class="card-body text-center">
                <h5 class="card-title">Gabriel da Vila</h5>
                <p class="card-text text-muted">Nutricionista Esportivo</p>
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
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
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
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
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
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
                <a href="agendamento.php" class="btn btn-sm btn-primary">Agendar Consulta</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Seus scripts -->
  <script type="module" src="../js/main.js"></script>
  <script src="../js/menu.js"></script>
</body>
</html>