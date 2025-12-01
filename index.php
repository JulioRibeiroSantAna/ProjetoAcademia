<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MEF - Saúde e Bem-estar</title>
  <link href="bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include 'includes-Gerais/navbar-dinamica.php'; ?>

  <main>
    <?php include 'includes-Gerais/hero-section.php'; ?>
    <?php include 'includes-Gerais/sobre.php'; ?>
    <?php include 'includes-Gerais/profissionais-section.php'; ?>
  </main>

  <?php include 'includes-Gerais/footer.php'; ?>

  <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.fade-in-up, .scale-in');

    function checkAnimation() {
      animatedElements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;

        if (elementPosition < screenPosition) {
          element.style.opacity = '1';
          element.style.transform = 'translateY(0) scale(1)';
        }
      });
    }

    checkAnimation();
    window.addEventListener('scroll', checkAnimation);
  });
  </script>
</body>
</html>