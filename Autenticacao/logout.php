<?php
// Autenticacao/logout.php
require_once '../config.php';

// Destruir a sessão
session_unset();
session_destroy();

// Redirecionar para a página inicial
header('Location: ../index.php');
exit;