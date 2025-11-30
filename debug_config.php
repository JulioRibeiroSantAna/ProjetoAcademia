<?php
/**
 * DEBUG - Verifica as constantes do config.php
 * Acesse: http://localhost:8080/debug_config.php
 */

echo "<h2>🔍 Debug de Configuração</h2>";

echo "<h3>1. Arquivo config.php carregado?</h3>";
if (file_exists(__DIR__ . '/config.php')) {
    echo "<span style='color: green;'>✅ config.php existe</span><br>";
    require_once __DIR__ . '/config.php';
    echo "<span style='color: green;'>✅ config.php foi carregado</span><br>";
} else {
    echo "<span style='color: red;'>❌ config.php NÃO ENCONTRADO</span><br>";
}

echo "<h3>2. Constantes definidas?</h3>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>Constante</th><th>Status</th><th>Valor</th></tr>";

$constants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'BASE_URL'];
foreach ($constants as $const) {
    $defined = defined($const);
    $status = $defined ? "<span style='color: green;'>✅ Definida</span>" : "<span style='color: red;'>❌ NÃO definida</span>";
    $value = $defined ? constant($const) : 'N/A';
    echo "<tr><td><strong>$const</strong></td><td>$status</td><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>3. String de conexão (DSN):</h3>";
if (defined('DB_HOST') && defined('DB_NAME')) {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ccc;'>$dsn</pre>";
    
    // Verifica caracteres invisíveis
    echo "<h4>Análise byte-a-byte do DSN:</h4>";
    echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ccc; font-family: monospace;'>";
    for ($i = 0; $i < strlen($dsn); $i++) {
        echo sprintf("Pos %02d: '%s' (ASCII: %03d)\n", $i, $dsn[$i], ord($dsn[$i]));
    }
    echo "</pre>";
} else {
    echo "<span style='color: red;'>❌ Não foi possível construir DSN - constantes não definidas</span>";
}

echo "<h3>4. Informações do ambiente:</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>Current Dir:</strong> " . __DIR__ . "</li>";
echo "<li><strong>Script:</strong> " . __FILE__ . "</li>";
echo "<li><strong>Hostname:</strong> " . gethostname() . "</li>";
echo "<li><strong>DOCKER_ENV:</strong> " . (getenv('DOCKER_ENV') ?: 'não definido') . "</li>";
echo "<li><strong>/.dockerenv:</strong> " . (file_exists('/.dockerenv') ? 'SIM' : 'NÃO') . "</li>";
echo "</ul>";

echo "<h3>5. Teste de conexão:</h3>";
if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_TIMEOUT => 5]
        );
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724;'>";
        echo "<strong>✅ CONEXÃO ESTABELECIDA COM SUCESSO!</strong>";
        echo "</div>";
    } catch (PDOException $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
        echo "<strong>❌ ERRO NA CONEXÃO:</strong><br>";
        echo htmlspecialchars($e->getMessage());
        echo "</div>";
    }
} else {
    echo "<span style='color: red;'>❌ Não foi possível testar - constantes não definidas</span>";
}
?>
