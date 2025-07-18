document.addEventListener('DOMContentLoaded', function() {
  // Filtro de categorias
  document.querySelectorAll('.filtro-categoria').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const categoria = this.value;
      const categoriaSection = document.querySelector(`[data-categoria="${categoria}"]`);
      categoriaSection.style.display = this.checked ? 'block' : 'none';
    });
  });

  // Pesquisa de vídeos
  document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const cards = document.querySelectorAll('.video-card');
    
    cards.forEach(card => {
      const title = card.querySelector('.card-title').textContent.toLowerCase();
      const description = card.querySelector('.card-text').textContent.toLowerCase();
      card.closest('.col-md-4').style.display = 
        title.includes(searchTerm) || description.includes(searchTerm) ? 'block' : 'none';
    });
  });

  // Upload de imagem
  const fileInput = document.getElementById('videoCapaUpload');
  const fileName = document.getElementById('videoCapaNome');
  const imagePreview = document.getElementById('videoCapaPreview');
  
  fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      fileName.textContent = `Arquivo: ${file.name}`;
      
      const reader = new FileReader();
      reader.onload = function(event) {
        imagePreview.src = event.target.result;
        imagePreview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  });
});