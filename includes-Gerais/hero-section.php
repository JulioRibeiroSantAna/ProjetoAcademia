<?php
// includes-Gerais/hero-section.php

// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para detectar o caminho base correto
function getBasePath() {
    $current_dir = __DIR__; // Diretório atual do arquivo
    $server_root = $_SERVER['DOCUMENT_ROOT'];
    
    // Se estamos na pasta includes-Gerais
    if (strpos($current_dir, 'includes-Gerais') !== false) {
        return '';
    }
    
    // Se estamos em uma subpasta (AdmLogado, UsuarioLogado, Autenticacao)
    return '../';
}

$base_path = getBasePath();

// Determinar a base URL dinamicamente
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = "$protocol://$host$script_path";

// Remover qualquer parte específica de pasta da base_url
$base_url = rtrim($base_url, '/');
if (strpos($base_url, '/AdmLogado') !== false) {
    $base_url = str_replace('/AdmLogado', '', $base_url);
}
if (strpos($base_url, '/UsuarioLogado') !== false) {
    $base_url = str_replace('/UsuarioLogado', '', $base_url);
}
if (strpos($base_url, '/Autenticacao') !== false) {
    $base_url = str_replace('/Autenticacao', '', $base_url);
}

// Definir variáveis padrão para usuário não logado
$titulo_principal = "PREPARE-SE PARA MUDAR";
$subtitulo = "PARA MELHOR.";
$botao_principal_texto = "Entrar na Plataforma";
$botao_principal_url = $base_url . "/Autenticacao/login.php";
$botao_secundario_texto = "Saiba Mais";
$botao_secundario_url = "#sobre";

// Verificar se o usuário está logado e ajustar o conteúdo
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $titulo_principal = "Bem-vindo(a) ao MEF";
        $subtitulo = "Área do Administrador";
        $botao_principal_texto = "Agendar Consulta";
        $botao_principal_url = $base_url . "/AdmLogado/agendamento-Adm.php";
        $botao_secundario_texto = "Bate-Papo";
        $botao_secundario_url = $base_url . "/AdmLogado/bate-papo-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $titulo_principal = "Bem-vindo(a) ao MEF";
        $subtitulo = "Área do Usuário";
        $botao_principal_texto = "Agendar Consulta";
        $botao_principal_url = $base_url . "/UsuarioLogado/agendamento.php";
        $botao_secundario_texto = "Bate-Papo";
        $botao_secundario_url = $base_url . "/UsuarioLogado/bate-papo.php";
    }
}
?>

<!-- Seção Hero -->
<section id="home" class="gradient-card d-flex align-items-center justify-content-center text-center text-white py-5" style="min-height: 100vh;">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4"><?php echo $titulo_principal; ?></h1>
        <h2 class="h3 mb-5"><?php echo $subtitulo; ?></h2>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?php echo $botao_principal_url; ?>" class="btn btn-primary btn-lg"><?php echo $botao_principal_texto; ?></a>
            <a href="<?php echo $botao_secundario_url; ?>" class="btn btn-outline-light btn-lg"><?php echo $botao_secundario_texto; ?></a>
        </div>
    </div>
</section>