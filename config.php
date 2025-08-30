<?php
// config.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações básicas
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = "$protocol://$host$script_path";

// Remove qualquer parte específica de pasta da base_url
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

// Define a constante BASE_URL
define('BASE_URL', $base_url);

// Configurações do banco de dados (quando for implementar)
define('DB_HOST', 'localhost');
define('DB_NAME', 'mef_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Inicializar variáveis de sessão se não existirem
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = '';
}
if (!isset($_SESSION['nome_usuario'])) {
    $_SESSION['nome_usuario'] = 'Usuário';
}
?>