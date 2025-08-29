<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento - MEF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css">
  <style>
    .agendamento-container {
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }
    .form-wrapper {
      flex: 1;
      margin-bottom: 2rem;
    }
    /* Estilos específicos para agendamento */
    .agendamento-form {
      max-width: 800px;
      margin: 40px auto;
      padding: 40px;
    }
    .form-control, .form-select {
      padding: 1rem 1.5rem !important;
      font-size: 1.1rem !important;
      border-radius: 12px !important;
    }
    .form-label {
      font-size: 1.1rem !important;
      font-weight: 600 !important;
      margin-bottom: 0.75rem !important;
    }
    .btn-save {
      padding: 1.25rem 2.5rem !important;
      font-size: 1.2rem !important;
    }
    h3 {
      font-size: 1.5rem !important;
      padding-bottom: 1rem !important;
      border-bottom: 2px solid rgba(255,255,255,0.2) !important;
    }
  </style>
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="container agendamento-container">
    <div class="gradient-card agendamento-form">
      <h1 class="text-center mb-4" style="color: white;">AGENDAMENTO</h1>
      <p class="text-center mb-4 lead"><strong>Preencha os campos para Agendar</strong></p>
      
      <form id="formAgendamento">
        <div class="mb-4">
          <h3 style="color: white;">Dados Pessoais</h3>
          <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" class="form-control" id="nome" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="tel" class="form-control" id="telefone" required>
          </div>
        </div>
        
        <div class="mb-4">
          <h3 style="color: white;">Agendamento</h3>
          <div class="mb-3">
            <label for="profissional" class="form-label">Profissional</label>
            <select class="form-select" id="profissional" required>
              <option value="" selected disabled>Selecione um profissional</option>
              <option value="Dr. Gabriel">Dr. Gabriel - Nutricionista</option>
              <option value="Dra. Ana">Dra. Ana - Nutricionista</option>
              <option value="Dr. Carlos">Dr. Carlos - Psicólogo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="data" class="form-label">Data</label>
            <select class="form-select" id="data" required>
              <option value="" selected disabled>Selecione uma data</option>
              <option value="2023-06-15">15/06/2023</option>
              <option value="2023-06-20">20/06/2023</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <select class="form-select" id="hora" required>
              <option value="" selected disabled>Selecione um horário</option>
              <option value="10:00">10:00</option>
              <option value="14:00">14:00</option>
            </select>
          </div>
        </div>
        
        <button type="submit" class="btn btn-save w-100">AGENDAR CONSULTA</button>
      </form>
    </div>  
  </main>

  <script src="../js/menu.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('formAgendamento').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Consulta agendada com sucesso!');
    });
  </script>
  <?php include '../includes-Gerais/footer.php'; ?>
</body>
</html>