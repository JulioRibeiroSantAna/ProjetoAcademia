<?php
/**
 * Configuração global do sistema
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_url = "$protocol://$host$script_path";
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

define('BASE_URL', $base_url);

// Configuração do banco de dados
// Detecta automaticamente se está em Docker ou ambiente local

// Carrega variáveis do .env se existir
function carregarEnv() {
    $envFile = __DIR__ . '/.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
            }
        }
    }
}

carregarEnv();

// Detecta se está rodando em Docker
function isDocker() {
    // Verifica se o hostname 'db' é resolvível (indicador de ambiente Docker)
    $host = gethostbyname('db');
    return $host !== 'db'; // Se resolveu, está em Docker
}

// Define configurações baseado no ambiente
if (isDocker()) {
    // Ambiente Docker: usa 'db' como hostname
    define('DB_HOST', 'db');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'sistema_nutricao');
    define('DB_USER', $_ENV['DB_USER'] ?? 'user');
    define('DB_PASS', $_ENV['DB_PASS'] ?? 'password');
} else {
    // Ambiente local (XAMPP/LAMP/etc): usa configurações do .env ou padrões
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'sistema_nutricao');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASS', $_ENV['DB_PASS'] ?? '');
}

define('MIN_PASSWORD_LENGTH', 6);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900);

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