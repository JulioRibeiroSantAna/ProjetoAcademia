<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO PRINCIPAL DO SISTEMA
 * 
 * Este arquivo é incluído em todas as páginas do site e contém:
 * - Configurações de URL base para navegação
 * - Credenciais do banco de dados
 * - Inicialização de sessões para login
 * - Variáveis de segurança
 * 
 * Sempre que uma página precisa acessar o banco de dados ou verificar
 * se o usuário está logado, ela inclui este arquivo primeiro.
 */

// Inicia a sessão do PHP se ainda não foi iniciada
// Sessão é como uma "memória" que guarda informações do usuário enquanto ele navega
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==================== CONFIGURAÇÃO DA URL BASE ==================== */
// Aqui eu construo a URL base do site automaticamente
// Isso é útil porque o site pode estar em localhost ou em um servidor real

// Verifica se o site está usando HTTPS (seguro) ou HTTP
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

// Pega o endereço do servidor (ex: localhost:8080 ou www.meusite.com)
$host = $_SERVER['HTTP_HOST'];

// Pega o caminho da pasta onde o site está (ex: /SiteAcademia)
$script_path = dirname($_SERVER['SCRIPT_NAME']);

// Junta tudo para formar a URL completa
$base_url = "$protocol://$host$script_path";

// Remove barras extras do final da URL para deixar limpo
$base_url = rtrim($base_url, '/');

// Remove nomes de pastas específicas da URL se elas aparecerem
// Isso garante que sempre tenhamos a URL da raiz do projeto
if (strpos($base_url, '/AdmLogado') !== false) {
    $base_url = str_replace('/AdmLogado', '', $base_url);
}
if (strpos($base_url, '/UsuarioLogado') !== false) {
    $base_url = str_replace('/UsuarioLogado', '', $base_url);
}
if (strpos($base_url, '/Autenticacao') !== false) {
    $base_url = str_replace('/Autenticacao', '', $base_url);
}

// Define BASE_URL como uma constante que pode ser usada em qualquer lugar
// Exemplo de uso: echo BASE_URL . "/index.php";
define('BASE_URL', $base_url);

/* ==================== CONFIGURAÇÃO DO BANCO DE DADOS ==================== */
// Estas são as credenciais para conectar ao banco de dados MySQL
// Estou usando Docker, então o host é 'db' (nome do container)
define('DB_HOST', 'db');              // Endereço do servidor do banco
define('DB_NAME', 'sistema_nutricao'); // Nome do banco de dados
define('DB_USER', 'user');            // Usuário do banco
define('DB_PASS', 'password');        // Senha do banco

/* ==================== CONFIGURAÇÕES DE SEGURANÇA ==================== */
// Aqui defino algumas regras de segurança para o sistema
define('MIN_PASSWORD_LENGTH', 6);     // Senha mínima de 6 caracteres
define('MAX_LOGIN_ATTEMPTS', 5);      // Máximo de 5 tentativas de login
define('LOGIN_TIMEOUT', 900);         // 15 minutos (900 segundos) de timeout

/* ==================== INICIALIZAÇÃO DAS VARIÁVEIS DE SESSÃO ==================== */
// Aqui eu crio variáveis de sessão vazias caso elas ainda não existam
// Isso evita erros quando o código tentar acessar essas variáveis

// Tipo de usuário: pode ser 'admin', 'usuario' ou vazio (não logado)
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = '';
}

// Nome completo do usuário logado
if (!isset($_SESSION['nome_usuario'])) {
    $_SESSION['nome_usuario'] = 'Usuário';
}

// Apelido do usuário (nome preferido para exibição)
if (!isset($_SESSION['apelido_usuario'])) {
    $_SESSION['apelido_usuario'] = '';
}

// Email do usuário logado
if (!isset($_SESSION['email_usuario'])) {
    $_SESSION['email_usuario'] = '';
}

// Telefone do usuário logado
if (!isset($_SESSION['telefone_usuario'])) {
    $_SESSION['telefone_usuario'] = '';
}

// Contador de tentativas de login (segurança contra ataques)
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Timestamp da última tentativa de login
if (!isset($_SESSION['last_login_attempt'])) {
    $_SESSION['last_login_attempt'] = 0;
}
?>