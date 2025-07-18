// utils.js - Funções utilitárias compartilhadas

// Mensagens de feedback
export function mostrarMensagem(tipo, mensagem) {
  const alertTypes = {
    success: 'alert-success',
    error: 'alert-danger',
    warning: 'alert-warning',
    info: 'alert-info'
  };

  const alertDiv = document.createElement('div');
  alertDiv.className = `alert ${alertTypes[tipo]} alert-dismissible fade show`;
  alertDiv.innerHTML = `
    ${mensagem}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  const container = document.getElementById('alertContainer') || document.body;
  container.prepend(alertDiv);
  
  setTimeout(() => {
    alertDiv.classList.remove('show');
    setTimeout(() => alertDiv.remove(), 150);
  }, 5000);
}

// Mostrar/esconder senha
export function configurarMostrarSenha() {
  document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function() {
      const input = this.previousElementSibling;
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        input.type = 'password';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      }
    });
  });
}

// Preview de foto de perfil
export function configurarPreviewFotoPerfil() {
  const inputFoto = document.querySelector('input[type="file"][accept="image/*"]');
  const preview = document.getElementById('fotoPreview');
  
  if (inputFoto && preview) {
    inputFoto.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }
}

// Scroll suave
export function configurarScrollSuave() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Formatar data
export function formatarData(dataString) {
  if (!dataString) return '';
  const [ano, mes, dia] = dataString.split('-');
  return `${dia}/${mes}/${ano}`;
}

// Inicializa utilitários comuns
export function initUtils() {
  configurarScrollSuave();
  
  // Outras inicializações globais...
  console.log('Utilitários inicializados');
}