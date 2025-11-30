<?php
/**
 * Logout - limpa sessão e redireciona
 */

require_once '../config.php';

$_SESSION = array();
session_destroy();

header('Location: ../index.php');
exit;
?>