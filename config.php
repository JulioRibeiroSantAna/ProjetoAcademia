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

// Configurações do banco de dados para ambiente Docker
define('DB_HOST', 'db');
define('DB_NAME', 'sistema_nutricao');
define('DB_USER', 'user');
define('DB_PASS', 'password');

// Configurações de segurança
define('MIN_PASSWORD_LENGTH', 6);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutos

// Inicializar variáveis de sessão se não existirem
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = '';
}
if (!isset($_SESSION['nome_usuario'])) {
    $_SESSION['nome_usuario'] = 'Usuário';
}
if (!isset($_SESSION['apelido_usuario'])) {
    $_SESSION['apelido_usuario'] = '';
}
if (!isset($_SESSION['email_usuario'])) {
    $_SESSION['email_usuario'] = '';
}
if (!isset($_SESSION['telefone_usuario'])) {
    $_SESSION['telefone_usuario'] = '';
}
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['last_login_attempt'])) {
    $_SESSION['last_login_attempt'] = 0;
}
?>