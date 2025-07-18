import { mostrarMensagem } from './utils.js';

// =======================
// Mostrar/esconder senha
// =======================
export function configurarMostrarSenha() {
  const senhaInput = document.getElementById('senha');
  const toggleCheck = document.getElementById('toggle');

  if (senhaInput && toggleCheck) {
    toggleCheck.addEventListener('change', () => {
      senhaInput.type = toggleCheck.checked ? 'text' : 'password';
    });
  }
}

// =======================
// Preview de foto de perfil
// =======================
export function configurarPreviewFotoPerfil() {
  const inputFoto = document.querySelector('input[type="file"][accept="image/*"]');
  const labelFoto = inputFoto?.previousElementSibling;

  if (inputFoto && labelFoto?.tagName === 'LABEL') {
    inputFoto.addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (ev) {
          labelFoto.style.backgroundImage = `url('${ev.target.result}')`;
        };
        reader.readAsDataURL(file);
      }
    });
  }
}