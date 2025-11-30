<?php
/**
 * Conexão com banco de dados MySQL via PDO
 */

require_once __DIR__ . '/config.php';

// Verifica se as constantes estão definidas
if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
    die('ERRO CRÍTICO: Constantes do banco de dados não foram definidas. Verifique o arquivo config.php');
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    $error_msg = 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    error_log($error_msg);
    
    // Mensagem amigável para o usuário
    die('
    <div style="font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">
        <h2>❌ Erro de Conexão com Banco de Dados</h2>
        <p><strong>Host:</strong> ' . DB_HOST . '</p>
        <p><strong>Database:</strong> ' . DB_NAME . '</p>
        <p><strong>Erro:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>
        <hr>
        <h3>Soluções:</h3>
        <ol>
            <li>Verifique se os containers estão rodando: <code>docker-compose ps</code></li>
            <li>Aguarde o MySQL iniciar completamente (30-60 segundos)</li>
            <li>Veja os logs: <code>docker-compose logs db</code></li>
            <li>Reinicie os containers: <code>docker-compose down && docker-compose up -d</code></li>
        </ol>
    </div>
    ');
}
?>