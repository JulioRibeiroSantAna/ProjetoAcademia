<?php
/**
 * Conexão com banco de dados MySQL via PDO
 * Com retry automático para ambientes Docker
 */

require_once __DIR__ . '/config.php';

// Verifica se as constantes estão definidas
if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
    die('ERRO CRÍTICO: Constantes do banco de dados não foram definidas. Verifique o arquivo config.php');
}

// Função para conectar com retry automático
function conectarComRetry($maxTentativas = 5, $intervalo = 3) {
    $ultimoErro = null;
    
    for ($tentativa = 1; $tentativa <= $maxTentativas; $tentativa++) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            $pdo->exec("SET time_zone = '-03:00'");
            return $pdo;
            
        } catch (PDOException $e) {
            $ultimoErro = $e;
            
            if ($tentativa < $maxTentativas) {
                error_log("[DB] Tentativa $tentativa/$maxTentativas falhou: " . $e->getMessage());
                sleep($intervalo);
            }
        }
    }
    
    throw $ultimoErro;
}

try {
    $pdo = conectarComRetry();
    
} catch (PDOException $e) {
    error_log('[DB FATAL] Conexão falhou após ' . $maxTentativas . ' tentativas: ' . $e->getMessage());
    
    $errorCode = $e->getCode();
    $errorMsg = $e->getMessage();
    
    // Mensagens específicas por tipo de erro
    $solucao = '';
    if (strpos($errorMsg, 'Unknown database') !== false) {
        $solucao = 'Banco de dados não existe. Execute: docker-compose down -v && docker-compose up -d';
    } elseif (strpos($errorMsg, 'Access denied') !== false) {
        $solucao = 'Usuário ou senha incorretos. Verifique docker-compose.yml';
    } elseif (strpos($errorMsg, 'Connection refused') !== false || strpos($errorMsg, "Can't connect") !== false) {
        $solucao = 'MySQL não está rodando. Execute: docker-compose up -d';
    } else {
        $solucao = 'Verifique os logs: docker-compose logs db';
    }
    
    die('
    <div style="font-family: monospace; padding: 20px; background: #1a1a1a; color: #ff6b6b; border-left: 4px solid #ff6b6b; margin: 20px;">
        <h2 style="color: #ff6b6b; margin: 0 0 15px 0;">⚠ Database Connection Failed</h2>
        <p style="margin: 5px 0;"><strong>Host:</strong> <code style="background: #2a2a2a; padding: 2px 6px;">' . DB_HOST . '</code></p>
        <p style="margin: 5px 0;"><strong>Database:</strong> <code style="background: #2a2a2a; padding: 2px 6px;">' . DB_NAME . '</code></p>
        <p style="margin: 5px 0;"><strong>User:</strong> <code style="background: #2a2a2a; padding: 2px 6px;">' . DB_USER . '</code></p>
        <hr style="border: 1px solid #333; margin: 15px 0;">
        <p style="margin: 5px 0;"><strong>Error:</strong> ' . htmlspecialchars($errorMsg) . '</p>
        <p style="margin: 5px 0;"><strong>Solution:</strong> ' . $solucao . '</p>
    </div>
    ');
}
?>