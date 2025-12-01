<?php
header('Content-Type: text/html; charset=UTF-8');
/**
 * ARQUIVO: videos-apoio.php
 * Página para assistir vídeos educativos
 * Mostra vídeos do YouTube e arquivos enviados
 */

require_once '../config.php';

// Só usuário comum acessa
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'usuario') {
    header('Location: ../Autenticacao/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vídeos de Apoio - MEF</title>
  <link href="../bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include '../includes-Gerais/navbar-dinamica.php'; ?>

  <main class="logged-container">
    <div class="container-symmetric">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
          <?php include '../includes-Gerais/videos-apoio-dinamico.php'; ?>
        </div>
      </div>
    </div>
  </main>

  <?php include '../includes-Gerais/footer.php'; ?>

  <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>