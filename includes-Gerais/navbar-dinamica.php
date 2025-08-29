<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determina a base URL dinamicamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = "$protocol://$host$script_path";

// Remove qualquer parte específica de pasta da base_url
$base_url = rtrim($base_url, '/');
if (strpos($base_url, '/AdmLogado') !== false) {
    $base_url = str_replace('/AdmLogado', '', $base_url);
}
if (strpos($base_url, '/UsuarioLogado') !== false) {
    $base_url = str_replace('/UsuarioLogado', '', $base_url);
}
if (strpos($base_url, '/Autenticacao') !== false) {
    $base_url = str_replace('/Autenticacao', '', $base_url);
}

// Define URLs padrão para usuários não logados
$url_inicio = $base_url . "/index.php";
$url_sobre = $base_url . "/index.php#sobre";
$url_profissionais = $base_url . "/index.php#profissionais";
$url_fale_conosco = $base_url . "/index.php#footer";

// Verifica se o usuário está logado e ajustar as URLs
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $url_inicio = $base_url . "/AdmLogado/logado-Adm.php";
        $url_sobre = $base_url . "/AdmLogado/logado-Adm.php#sobre";
        $url_profissionais = $base_url . "/AdmLogado/logado-Adm.php#profissionais";
        $url_fale_conosco = $base_url . "/AdmLogado/logado-Adm.php#footer";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_inicio = $base_url . "/UsuarioLogado/logado.php";
        $url_sobre = $base_url . "/UsuarioLogado/logado.php#sobre";
        $url_profissionais = $base_url . "/UsuarioLogado/logado.php#profissionais";
        $url_fale_conosco = $base_url . "/UsuarioLogado/logado.php#footer";
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
        $url_meus_agendamentos = $base_url . "/AdmLogado/meus-agendamentos-Adm.php";
        $url_perfil = $base_url . "/AdmLogado/perfil-Adm.php";
        $url_editar_perfil = $base_url . "/AdmLogado/editar-perfil-Adm.php";
        $url_videos_apoio = $base_url . "/AdmLogado/videos-apoio-Adm.php";
        $url_agendamento = $base_url . "/AdmLogado/agendamento-Adm.php";
        $url_bate_papo = $base_url . "/AdmLogado/bate-papo-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_meus_agendamentos = $base_url . "/UsuarioLogado/meus-agendamentos.php";
        $url_perfil = $base_url . "/UsuarioLogado/perfil.php";
        $url_editar_perfil = $base_url . "/UsuarioLogado/editar-perfil.php";
        $url_videos_apoio = $base_url . "/UsuarioLogado/videos-apoio.php";
        $url_agendamento = $base_url . "/UsuarioLogado/agendamento.php";
        $url_bate_papo = $base_url . "/UsuarioLogado/bate-papo.php";
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
                <li><a class="dropdown-item" href="' . $url_agendamento . '">Agendar Consulta</a></li>
                <li><a class="dropdown-item" href="' . $url_bate_papo . '">Bate-Papo / Gerenciamento</a></li>
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
                <li><a class="dropdown-item" href="' . $url_bate_papo . '">Bate-Papo</a></li>
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
    <li><a class="dropdown-item text-danger" href="' . $base_url . '/Autenticacao/logout.php">Sair</a></li>';
    
    // Constroi o menu dropdown completo para usuários logados
    $menu_dropdown = '
    <div class="dropdown ms-3">
        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> ' . htmlspecialchars($_SESSION['nome_usuario'] ?? 'Menu') . '
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
    <a href="' . $base_url . '/Autenticacao/login.php" class="btn btn-light">
        <i class="bi bi-person-circle me-1"></i> Entrar
    </a>';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $url_inicio; ?>">MEF</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url_sobre; ?>">Sobre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url_profissionais; ?>">Profissionais</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url_fale_conosco; ?>">Fale Conosco</a>
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
.navbar {
  background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.navbar-brand {
  font-weight: bold;
  font-size: 1.5rem;
}
.nav-link {
  font-weight: 500;
  transition: color 0.3s ease;
}
.nav-link:hover {
  color: rgba(255, 255, 255, 0.8) !important;
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