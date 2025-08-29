<?php
// config.php
session_start();

// Configurações básicas
$base_url = 'http://localhost/SiteAcademia'; // Ajuste conforme seu ambiente

// Configurações do banco de dados (quando for implementar)
define('DB_HOST', 'localhost');
define('DB_NAME', 'mef_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Inicializar variáveis de sessão se não existirem
if (!isset($_SESSION['tipo_usuario'])) {
    $_SESSION['tipo_usuario'] = '';
}
?>