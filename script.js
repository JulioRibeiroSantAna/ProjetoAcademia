// Redirecionamento para a página de login
document.getElementById('entrar').addEventListener('click', function () {
    window.location.href = "login.html";
});

// Scroll suave ao clicar nos links do menu de navegação
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetSection = document.querySelector(this.getAttribute('href'));
        targetSection.scrollIntoView({ behavior: 'smooth' });
    });
});

// Exibir alerta ao clicar no botão de login na página de login
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("form");
    
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("Login realizado com sucesso!");
        });
    }
});

// Alternar entre mostrar/esconder senha
document.addEventListener("DOMContentLoaded", function () {
    const senhaInput = document.getElementById("senha");
    const toggleCheck = document.getElementById("toggle");

    if (toggleCheck && senhaInput) {
        toggleCheck.addEventListener("change", function () {
            senhaInput.type = this.checked ? "text" : "password";
        });
    }
});

document.getElementById('cadastrar').addEventListener('click', function () {
  window.location.href = "cadastro.html";
});
