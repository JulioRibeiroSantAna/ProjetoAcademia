// main.js - Ponto de entrada principal (versão simplificada)

// 1. Importação dos módulos essenciais
import { initUtils } from './utils.js';
import { setupMenu } from './menu.js';
import { setupNavigation } from './navigation.js';
import { setupForms } from './forms.js';

// 2. Inicialização principal
document.addEventListener('DOMContentLoaded', function() {
  // Funções globais (todas as páginas)
  initUtils();
  setupMenu();
  setupNavigation();
  
  // Identifica a página atual
  const currentPage = window.location.pathname.split('/').pop();
  
  // 3. Carrega scripts específicos (apenas forms, já que chat/videos são importados diretamente)
  switch(currentPage) {
    // Páginas que usam forms
    case 'editar-perfil.html':
    case 'perfil.html':
    case 'agendamento.html':
      setupForms();
      break;
      
    // Página inicial
    case 'logado.html':
    case 'index.html':
      // Inicializações específicas se necessário
      break;
      
    default:
      console.log('Página sem scripts específicos no main.js:', currentPage);
  }
  
  console.log('Sistema básico inicializado para:', currentPage);
});