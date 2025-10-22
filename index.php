<?php
/**
 * PÁGINA INICIAL DO SITE (INDEX.PHP)
 * 
 * Esta é a primeira página que aparece quando alguém acessa o site.
 * Ela mostra:
 * - Barra de navegação (navbar)
 * - Seção hero (banner principal com título grande)
 * - Seção sobre (informações sobre a plataforma)
 * - Seção de profissionais (cards com nossos nutricionistas)
 * - Rodapé (footer)
 * 
 * O usuário NÃO precisa estar logado para ver esta página.
 */

// Inclui o arquivo de configuração para ter acesso às sessões e constantes
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <!-- Define o charset UTF-8 para suportar acentos e caracteres especiais -->
  <meta charset="UTF-8">
  
  <!-- Torna o site responsivo em celulares e tablets -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Título que aparece na aba do navegador -->
  <title>MEF - Saúde e Bem-estar</title>
  
  <!-- Framework Bootstrap para deixar o site bonito e responsivo -->
  <link href="bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Biblioteca de ícones do Bootstrap (usamos em botões e menus) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  
  <!-- Fonte Poppins do Google Fonts (deixa o texto mais moderno) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Meu CSS personalizado com os estilos do site -->
  <!-- O ?v= é para forçar o navegador a recarregar o CSS quando eu faço mudanças -->
  <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
  <!-- BARRA DE NAVEGAÇÃO (MENU DO TOPO) -->
  <!-- Incluo o arquivo da navbar que muda dependendo se o usuário está logado -->
  <?php include 'includes-Gerais/navbar-dinamica.php'; ?>

  <!-- CONTEÚDO PRINCIPAL DA PÁGINA -->
  <main>
    <!-- SEÇÃO HERO (Banner principal com título grande e botões) -->
    <!-- Esta seção muda o texto dependendo se o usuário está logado -->
    <?php include 'includes-Gerais/hero-section.php'; ?>

    <!-- SEÇÃO SOBRE (Informações sobre a plataforma MEF) -->
    <!-- Explica o que é o sistema e o que ele oferece -->
    <?php include 'includes-Gerais/sobre.php'; ?>

    <!-- SEÇÃO DE PROFISSIONAIS (Cards com os nutricionistas) -->
    <!-- Mostra 4 profissionais cadastrados no banco de dados -->
    <?php include 'includes-Gerais/profissionais-section.php'; ?>
  </main>

  <!-- RODAPÉ (Footer com redes sociais e informações) -->
  <?php include 'includes-Gerais/footer.php'; ?>

  <!-- JavaScript do Bootstrap (necessário para menus dropdown e modais) -->
  <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

  <!-- SCRIPT DE ANIMAÇÃO -->
  <!-- Este código faz os elementos aparecerem suavemente quando o usuário faz scroll -->
  <script>
  // Espera o HTML carregar completamente antes de executar
  document.addEventListener('DOMContentLoaded', function() {
    
    // Seleciona todos os elementos que têm animação
    const animatedElements = document.querySelectorAll('.fade-in-up, .scale-in');

    // Função que verifica se o elemento está visível na tela
    function checkAnimation() {
      animatedElements.forEach(element => {
        // Pega a posição do elemento em relação à tela
        const elementPosition = element.getBoundingClientRect().top;
        
        // Define quando o elemento deve aparecer (quando chegar a 1/1.3 da tela)
        const screenPosition = window.innerHeight / 1.3;

        // Se o elemento estiver visível, aplica a animação
        if (elementPosition < screenPosition) {
          element.style.opacity = '1';                    // Torna visível
          element.style.transform = 'translateY(0) scale(1)'; // Remove deslocamento
        }
      });
    }

    // Executa a verificação quando a página carrega
    checkAnimation();
    
    // Executa a verificação toda vez que o usuário faz scroll
    window.addEventListener('scroll', checkAnimation);
  });
  </script>
</body>
</html>