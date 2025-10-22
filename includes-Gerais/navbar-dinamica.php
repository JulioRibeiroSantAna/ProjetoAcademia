<?php
/**
 * ARQUIVO: navbar-dinamica.php
 * LOCALIZAÇÃO: includes-Gerais/
 * 
 * PROPÓSITO:
 * Esta é a barra de navegação (menu do topo) do site.
 * O nome "dinâmica" significa que ela MUDA dependendo de quem está acessando.
 * 
 * 3 VERSÕES DA NAVBAR:
 * 1. VISITANTE (não logado): Mostra links pro index + botão "Entrar"
 * 2. USUÁRIO COMUM (logado): Mostra menu com Vídeos, Perfil, Agendamentos
 * 3. ADMIN (logado): Mostra menu + opção "Gerenciar Profissionais"
 * 
 * COMO FUNCIONA:
 * - Verifica $_SESSION['tipo_usuario']
 * - Monta URLs diferentes pra cada tipo
 * - Cria menu dropdown personalizado
 * 
 * TECNOLOGIAS:
 * - PHP para lógica condicional
 * - Bootstrap 5 para navbar responsiva
 * - JavaScript para submenus
 * 
 * USADA EM:
 * - TODAS as páginas do site (include no topo)
 */

/**
 * VERIFICAÇÃO DE SESSÃO
 * 
 * Garante que a sessão está iniciada antes de tentar ler $_SESSION.
 * session_status() retorna o estado atual da sessão.
 * PHP_SESSION_NONE significa que a sessão NÃO foi iniciada ainda.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * INCLUIR CONFIG.PHP SE NECESSÁRIO
 * 
 * A constante BASE_URL é definida no config.php.
 * Se ela não existe, significa que config.php não foi incluído.
 * Nesse caso, incluímos aqui.
 * 
 * __DIR__ retorna o diretório atual (includes-Gerais)
 * ../ volta um nível (pra raiz do projeto)
 */
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

/**
 * DEFINIR URLs PADRÃO (PARA VISITANTES)
 * 
 * Quando o usuário NÃO está logado, os links apontam pro index.php.
 * As âncoras (#sobre, #profissionais) fazem scroll pra seção certa.
 * 
 * EXEMPLO:
 * Visitante clica em "Sobre" → vai pra index.php#sobre → scroll até a seção
 */
$url_inicio = BASE_URL . "/index.php";
$url_sobre = BASE_URL . "/index.php#sobre";
$url_profissionais = BASE_URL . "/index.php#profissionais";
$url_fale_conosco = BASE_URL . "/index.php#footer";

// Ajusta URLs se o usuário estiver logado
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        // Admin: usa páginas da pasta AdmLogado
        $url_inicio = BASE_URL . "/AdmLogado/logado-Adm.php";
        $url_sobre = BASE_URL . "/AdmLogado/logado-Adm.php#sobre";
        $url_profissionais = BASE_URL . "/AdmLogado/logado-Adm.php#profissionais";
        $url_fale_conosco = BASE_URL . "/AdmLogado/logado-Adm.php#footer";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        // Usuário comum: usa páginas da pasta UsuarioLogado
        $url_inicio = BASE_URL . "/UsuarioLogado/logado.php";
        $url_sobre = BASE_URL . "/UsuarioLogado/logado.php#sobre";
        $url_profissionais = BASE_URL . "/UsuarioLogado/logado.php#profissionais";
        $url_fale_conosco = BASE_URL . "/UsuarioLogado/logado.php#footer";
    }
}

// Inicializa variáveis para o menu dropdown
$menu_videos_apoio = "";
$menu_profissionais = "";
$menu_perfil = "";
$menu_logout = "";
$menu_dropdown = "";

// URLs dinâmicas baseadas no tipo de usuário
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $url_meus_agendamentos = BASE_URL . "/AdmLogado/meus-agendamentos-Adm.php";
        $url_perfil = BASE_URL . "/AdmLogado/perfil-Adm.php";
        $url_editar_perfil = BASE_URL . "/AdmLogado/editar-perfil-Adm.php";
        $url_videos_apoio = BASE_URL . "/AdmLogado/videos-apoio-Adm.php";
        $url_agendamento = BASE_URL . "/AdmLogado/agendamento-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_meus_agendamentos = BASE_URL . "/UsuarioLogado/meus-agendamentos.php";
        $url_perfil = BASE_URL . "/UsuarioLogado/perfil.php";
        $url_editar_perfil = BASE_URL . "/UsuarioLogado/editar-perfil.php";
        $url_videos_apoio = BASE_URL . "/UsuarioLogado/videos-apoio.php";
        $url_agendamento = BASE_URL . "/UsuarioLogado/agendamento.php";
    }
}

// Verifica se o usuário está logado para construir o menu dropdown
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    // URLs para usuários logados
    if ($_SESSION['tipo_usuario'] === 'admin') {
        // Menu dropdown para admin
        $menu_videos_apoio = '<li><a class="dropdown-item" href="' . $url_videos_apoio . '">Vídeos de Apoio / Gerenciamento</a></li>';
        $menu_profissionais = '
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Profissionais</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="' . BASE_URL . '/AdmLogado/profissionais-Adm.php">Gerenciar Profissionais</a></li>
                <li><a class="dropdown-item" href="' . $url_agendamento . '">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="' . $url_meus_agendamentos . '">Meus Agendamentos</a></li>
            </ul>
        </li>';
        $menu_perfil = '
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Perfil</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="' . $url_perfil . '">Ver Perfil</a></li>
                <li><a class="dropdown-item" href="' . $url_editar_perfil . '">Editar Perfil</a></li>
            </ul>
        </li>';
        
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        // Menu dropdown para usuário comum
        $menu_videos_apoio = '<li><a class="dropdown-item" href="' . $url_videos_apoio . '">Vídeos de Apoio</a></li>';
        $menu_profissionais = '
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Profissionais</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="' . $url_agendamento . '">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="' . $url_meus_agendamentos . '">Meus Agendamentos</a></li>
            </ul>
        </li>';
        $menu_perfil = '
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Perfil</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="' . $url_perfil . '">Ver Perfil</a></li>
                <li><a class="dropdown-item" href="' . $url_editar_perfil . '">Editar Perfil</a></li>
            </ul>
        </li>';
    }
    
    // Logout é comum para ambos os tipos de usuários logados
    $menu_logout = '
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="' . BASE_URL . '/Autenticacao/logout.php">Sair</a></li>';
    
    // LÓGICA CORRIGIDA DO APELIDO
    $nome_exibicao = 'Menu';
    if (isset($_SESSION['apelido_usuario']) && !empty(trim($_SESSION['apelido_usuario']))) {
        $nome_exibicao = $_SESSION['apelido_usuario'];
    } elseif (isset($_SESSION['nome_usuario']) && !empty(trim($_SESSION['nome_usuario']))) {
        $nome_exibicao = $_SESSION['nome_usuario'];
    }
    
    $menu_dropdown = '
    <div class="dropdown ms-3">
        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle me-1"></i> ' . htmlspecialchars($nome_exibicao) . '
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
          ' . $menu_videos_apoio . '
          ' . $menu_profissionais . '
          ' . $menu_perfil . '
          ' . $menu_logout . '
        </ul>
    </div>';
} else {
    // Botão de login para usuários não logados
    $menu_dropdown = '
    <a href="' . BASE_URL . '/Autenticacao/login.php" class="btn btn-light">
        <i class="bi bi-person-circle me-1"></i> Entrar
    </a>';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-mef">
  <div class="container">
    <a class="navbar-brand navbar-brand-mef" href="<?php echo htmlspecialchars($url_inicio); ?>">MEF</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-link-mef" href="<?php echo htmlspecialchars($url_sobre); ?>">Sobre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-mef" href="<?php echo htmlspecialchars($url_profissionais); ?>">Profissionais</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-mef" href="<?php echo htmlspecialchars($url_fale_conosco); ?>">Fale Conosco</a>
        </li>
      </ul>
      <?php echo $menu_dropdown; ?>
    </div>
  </div>
</nav>

<style>
.dropdown-submenu {
  position: relative;
}
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-left: 0.1rem;
  margin-right: 0.1rem;
}
@media (max-width: 991px) {
  .dropdown-submenu > .dropdown-menu {
    left: 0;
    margin-left: 1rem;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var dropdownSubmenus = document.querySelectorAll('.dropdown-submenu .dropdown-toggle');
  dropdownSubmenus.forEach(function (dropdownToggle) {
    dropdownToggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var submenu = this.nextElementSibling;
      if (submenu) {
        submenu.classList.toggle('show');
      }

      document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function (menu) {
        if (menu !== submenu) menu.classList.remove('show');
      });
    });
  });

  document.addEventListener('click', function () {
    document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(function (menu) {
      menu.classList.remove('show');
    });
  });

  // Fechar menu ao clicar em um item (para mobile)
  document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(function(item) {
    item.addEventListener('click', function() {
      var navbarCollapse = document.getElementById('navbarContent');
      if (navbarCollapse.classList.contains('show')) {
        var bsCollapse = new bootstrap.Collapse(navbarCollapse, {
          toggle: false
        });
        bsCollapse.hide();
      }
    });
  });
});
</script>