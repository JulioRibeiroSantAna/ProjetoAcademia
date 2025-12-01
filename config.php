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
// Prioridade: Variáveis de ambiente Docker > Arquivo .env > Padrões

// Carrega .env se existir (apenas para ambiente local)
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        if (!isset($_ENV[$key]) && !getenv($key)) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

// Detecta ambiente Docker (múltiplas verificações)
$isDocker = (
    getenv('MYSQL_HOST') !== false || 
    isset($_ENV['MYSQL_HOST']) ||
    getenv('DB_HOST') === 'db' ||
    isset($_ENV['DB_HOST']) && $_ENV['DB_HOST'] === 'db' ||
    file_exists('/.dockerenv')
);

if ($isDocker) {
    // Docker: tenta múltiplas variáveis
    define('DB_HOST', 
        getenv('DB_HOST') ?: 
        getenv('MYSQL_HOST') ?: 
        ($_ENV['DB_HOST'] ?? ($_ENV['MYSQL_HOST'] ?? 'db'))
    );
    define('DB_NAME', 
        getenv('DB_NAME') ?: 
        getenv('MYSQL_DATABASE') ?: 
        ($_ENV['DB_NAME'] ?? ($_ENV['MYSQL_DATABASE'] ?? 'sistema_nutricao'))
    );
    define('DB_USER', 
        getenv('DB_USER') ?: 
        getenv('MYSQL_USER') ?: 
        ($_ENV['DB_USER'] ?? ($_ENV['MYSQL_USER'] ?? 'user'))
    );
    define('DB_PASS', 
        getenv('DB_PASS') ?: 
        getenv('MYSQL_PASSWORD') ?: 
        ($_ENV['DB_PASS'] ?? ($_ENV['MYSQL_PASSWORD'] ?? 'password'))
    );
} else {
    // Local: usa .env ou padrões XAMPP
    define('DB_HOST', getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost'));
    define('DB_NAME', getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'sistema_nutricao'));
    define('DB_USER', getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root'));
    define('DB_PASS', getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? ''));
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