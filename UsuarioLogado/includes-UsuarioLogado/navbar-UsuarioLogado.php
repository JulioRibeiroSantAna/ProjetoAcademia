<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="logado.php">MEF</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="logado.php#sobre">Sobre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logado.php#profissionais">Profissionais</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#footer">Fale Conosco</a>
        </li>
      </ul>
      <div class="dropdown ms-3">
        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle me-1"></i> Menu
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
          <li><a class="dropdown-item" href="videos-apoio.php">Vídeos de Apoio</a></li>
          <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Profissionais</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="agendamento.php">Agendar Consulta</a></li>
              <li><a class="dropdown-item" href="bate-papo.php">Bate-Papo</a></li>
              <li><a class="dropdown-item" href="meus-agendamentos.php">Meus Agendamentos</a></li>
            </ul>
          </li>
          <li><a class="dropdown-item" href="perfil.php">Perfil de Usuário</a></li>
          <li><hr class="dropdown-divider"></li>
          <!-- CORREÇÃO: Link para logout.php -->
          <li><a class="dropdown-item text-danger" href="../Autenticacao/logout.php">Sair</a></li>
        </ul>
      </div>
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
});
</script>