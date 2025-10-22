<?php
/**
 * ARQUIVO: logout.php
 * Faz o logout do usuário e limpa a sessão
 */

require_once '../config.php';

// Limpa todas as variáveis da sessão
$_SESSION = array();

// Destroi a sessão no servidor
session_destroy();

// Redireciona pro início
header('Location: ../index.php');
exit;
?>