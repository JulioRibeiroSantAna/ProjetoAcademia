<?php
// includes-Gerais/hero-section.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

$titulo_principal = "PREPARE-SE PARA MUDAR";
$subtitulo = "PARA MELHOR.";
$botao_principal_texto = "Entrar na Plataforma";
$botao_principal_url = BASE_URL . "/Autenticacao/login.php";
$botao_secundario_texto = "Saiba Mais";
$botao_secundario_url = "#sobre";

if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $titulo_principal = "Bem-vindo(a) ao MEF";
        $subtitulo = "Área do Administrador";
        $botao_principal_texto = "Agendar Consulta";
        $botao_principal_url = BASE_URL . "/AdmLogado/agendamento-Adm.php";
        $botao_secundario_texto = "Bate-Papo";
        $botao_secundario_url = BASE_URL . "/AdmLogado/bate-papo-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $titulo_principal = "Bem-vindo(a) ao MEF";
        $subtitulo = "Área do Usuário";
        $botao_principal_texto = "Agendar Consulta";
        $botao_principal_url = BASE_URL . "/UsuarioLogado/agendamento.php";
        $botao_secundario_texto = "Bate-Papo";
        $botao_secundario_url = BASE_URL . "/UsuarioLogado/bate-papo.php";
    }
}
?>

<!-- Seção Hero -->
<section id="home" class="hero-section section-spacing">
    <div class="container-symmetric">
        <div class="hero-content fade-in-up">
            <h1><?php echo htmlspecialchars($titulo_principal); ?></h1>
            <h2><?php echo htmlspecialchars($subtitulo); ?></h2>
            <div class="hero-buttons">
                <a href="<?php echo htmlspecialchars($botao_principal_url); ?>" class="btn mef-btn-primary btn-lg"><?php echo htmlspecialchars($botao_principal_texto); ?></a>
                <a href="<?php echo htmlspecialchars($botao_secundario_url); ?>" class="btn btn-outline-light btn-lg"><?php echo htmlspecialchars($botao_secundario_texto); ?></a>
            </div>
        </div>
    </div>
</section>