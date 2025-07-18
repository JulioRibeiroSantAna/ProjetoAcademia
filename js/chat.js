document.addEventListener('DOMContentLoaded', function() {
  const chatArea = document.getElementById('chatMessages');
  const inputMsg = document.getElementById('messageInput');
  const btnEnviar = document.getElementById('sendButton');
  
  let messages = [];
  let selectedMessageId = null;

  // Adiciona mensagem ao chat
  function addMessage(sender, text, id = null, isEdited = false) {
    const msgId = id || Date.now();
    const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    const msgDiv = document.createElement('div');
    msgDiv.className = `message ${sender} mb-3`;
    msgDiv.dataset.id = msgId;
    
    let msgContent = `
      <div class="message-content ${sender === 'user' ? 'user-message' : 'professional-message'}">
        <div class="message-text">${text}</div>
        <div class="message-meta d-flex align-items-center mt-1">
          <small class="message-time text-muted">${time}</small>
          ${isEdited ? '<small class="text-muted ms-2">(editado)</small>' : ''}
    `;
    
    if (sender === 'user') {
      msgContent += `
          <button class="btn-edit-message ms-auto btn btn-sm btn-link text-light p-0" data-id="${msgId}">
            <i class="bi bi-three-dots-vertical"></i>
          </button>
      `;
    }
    
    msgContent += `</div></div>`;
    msgDiv.innerHTML = msgContent;
    
    // Atualiza ou adiciona mensagem
    const existingMsg = document.querySelector(`.message[data-id="${msgId}"]`);
    if (existingMsg) {
      existingMsg.replaceWith(msgDiv);
    } else {
      chatArea.appendChild(msgDiv);
      messages.push({ id: msgId, sender, text });
    }
    
    chatArea.scrollTop = chatArea.scrollHeight;
    
    // Configura eventos
    if (sender === 'user') {
      const editBtn = msgDiv.querySelector('.btn-edit-message');
      editBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        showMessageOptions(msgId);
      });
      
      msgDiv.addEventListener('click', () => showMessageOptions(msgId));
    }
  }

  // Mostra opções da mensagem
  function showMessageOptions(msgId) {
    // Remove seleção anterior
    if (selectedMessageId) {
      hideMessageOptions(selectedMessageId);
    }
    
    // Seleciona nova mensagem
    selectedMessageId = msgId;
    const msgElement = document.querySelector(`.message[data-id="${msgId}"] .message-content`);
    msgElement.classList.add('message-selected');
    
    // Cria menu de opções
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'message-options d-flex gap-2 justify-content-end mt-2';
    optionsDiv.innerHTML = `
      <button class="btn-option btn-edit btn btn-sm btn-primary" data-id="${msgId}">
        <i class="bi bi-pencil"></i> Editar
      </button>
      <button class="btn-option btn-delete btn btn-sm btn-danger" data-id="${msgId}">
        <i class="bi bi-trash"></i> Excluir
      </button>
    `;
    
    // Adiciona ao DOM
    msgElement.parentNode.appendChild(optionsDiv);
    
    // Configura eventos
    document.querySelector(`.btn-edit[data-id="${msgId}"]`).addEventListener('click', (e) => {
      e.stopPropagation();
      editMessage(msgId);
    });
    
    document.querySelector(`.btn-delete[data-id="${msgId}"]`).addEventListener('click', (e) => {
      e.stopPropagation();
      deleteMessage(msgId);
    });
  }

  // Esconde opções da mensagem
  function hideMessageOptions(msgId) {
    const msgElement = document.querySelector(`.message[data-id="${msgId}"] .message-content`);
    if (msgElement) {
      msgElement.classList.remove('message-selected');
      
      const optionsDiv = msgElement.parentNode.querySelector('.message-options');
      if (optionsDiv) optionsDiv.remove();
    }
    selectedMessageId = null;
  }

  // Edita mensagem
  function editMessage(msgId) {
    const msg = messages.find(m => m.id === msgId);
    if (!msg) return;
    
    const msgDiv = document.querySelector(`.message[data-id="${msgId}"]`);
    msgDiv.innerHTML = `
      <div class="message-content editing-message bg-primary p-3 rounded">
        <input type="text" class="form-control mb-2 bg-dark text-light" value="${msg.text}" id="editMsgInput">
        <div class="edit-controls d-flex gap-2">
          <button class="btn btn-sm btn-success" id="saveEditMsg">
            <i class="bi bi-check"></i> Salvar
          </button>
          <button class="btn btn-sm btn-secondary" id="cancelEditMsg">
            <i class="bi bi-x"></i> Cancelar
          </button>
        </div>
      </div>
    `;
    
    const editInput = document.getElementById('editMsgInput');
    editInput.focus();
    editInput.select();
    
    // Configura eventos
    document.getElementById('saveEditMsg').addEventListener('click', () => {
      const newText = editInput.value.trim();
      if (newText && newText !== msg.text) {
        msg.text = newText;
        addMessage(msg.sender, newText, msg.id, true);
      } else {
        addMessage(msg.sender, msg.text, msg.id);
      }
    });
    
    document.getElementById('cancelEditMsg').addEventListener('click', () => {
      addMessage(msg.sender, msg.text, msg.id);
    });
  }

  // Exclui mensagem
  function deleteMessage(msgId) {
    const msgIndex = messages.findIndex(m => m.id === msgId);
    if (msgIndex !== -1) {
      messages.splice(msgIndex, 1);
    }
    
    const msgDiv = document.querySelector(`.message[data-id="${msgId}"]`);
    if (msgDiv) {
      msgDiv.classList.add('message-deleting');
      setTimeout(() => msgDiv.remove(), 300);
    }
  }

  // Envia mensagem
  function sendMessage() {
    const text = inputMsg.value.trim();
    if (text) {
      addMessage('user', text);
      inputMsg.value = '';
      inputMsg.focus();
      
      // Simula respostas variadas do profissional
      setTimeout(() => {
        const responses = [
          "Recebi sua mensagem. Estou analisando...",
          "Ótima pergunta! Deixe-me verificar...",
          "Pode me dar mais detalhes sobre isso?",
          "Vou consultar isso para você",
          "Estou pesquisando a melhor resposta para você"
        ];
        const randomResponse = responses[Math.floor(Math.random() * responses.length)];
        addMessage('professional', randomResponse);
      }, 1000 + Math.random() * 2000);
    }
  }

  // Configura eventos
  btnEnviar.addEventListener('click', sendMessage);
  
  inputMsg.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  // Fecha menu ao clicar fora
  document.addEventListener('click', (e) => {
    if (selectedMessageId && !e.target.closest(`.message[data-id="${selectedMessageId}"]`)) {
      hideMessageOptions(selectedMessageId);
    }
  });

  // Mensagem inicial do profissional
  addMessage('professional', 'Olá! Como posso te ajudar hoje?');
});