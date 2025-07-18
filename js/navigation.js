import { mostrarMensagem } from './utils.js';

// =======================
// Redirecionamentos básicos
// =======================
export function configurarRedirecionamentos() {
  document.getElementById('entrar')?.addEventListener('click', () => {
    window.location.href = "login.html";
  });

  document.getElementById('cadastrar')?.addEventListener('click', () => {
    window.location.href = "cadastro.html";
  });
}

// =======================
// Scroll suave para âncoras
// =======================
export function configurarScrollSuave() {
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  });
}   