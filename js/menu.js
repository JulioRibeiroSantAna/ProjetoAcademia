// menu.js - Controle do menu dropdown e submenu

export function configurarMenu() {
  // Configuração do submenu para desktop e mobile
  const submenuToggles = document.querySelectorAll('.dropdown-submenu > .dropdown-item');
  
  submenuToggles.forEach(toggle => {
    // Remove eventos anteriores para evitar duplicação
    toggle.removeEventListener('mouseenter', handleMouseEnter);
    toggle.removeEventListener('click', handleClick);
    
    // Adiciona novos eventos
    toggle.addEventListener('mouseenter', handleMouseEnter);
    toggle.addEventListener('click', handleClick);
  });
  
  // Fechar submenus ao clicar fora
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-submenu')) {
      closeAllSubmenus();
    }
  });
  
  // Funções auxiliares
  function handleMouseEnter() {
    if (window.innerWidth > 768) { // Desktop
      closeAllSubmenus();
      this.nextElementSibling.style.display = 'block';
    }
  }
  
  function handleClick(e) {
    if (window.innerWidth <= 768) { // Mobile
      e.preventDefault();
      e.stopPropagation();
      
      const isOpen = this.nextElementSibling.style.display === 'block';
      closeAllSubmenus();
      
      if (!isOpen) {
        this.nextElementSibling.style.display = 'block';
      }
    }
  }
  
  function closeAllSubmenus() {
    document.querySelectorAll('.dropdown-submenu .dropdown-menu').forEach(menu => {
      menu.style.display = 'none';
    });
  }
  
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
}