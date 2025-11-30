<?php
/**
 * Script de teste de conexão com banco de dados
 * Acesse: http://localhost:8080/test_connection.php
 */

echo "<h2>Teste de Conexão com Banco de Dados</h2>";

// Lista de hosts para testar
$hosts = ['db', 'siteacademia_db', 'localhost', '127.0.0.1'];
$db_name = 'sistema_nutricao';
$db_user = 'user';
$db_pass = 'password';

echo "<h3>Informações do Ambiente:</h3>";
echo "<ul>";
echo "<li><strong>DOCKER_ENV:</strong> " . (getenv('DOCKER_ENV') ?: 'não definido') . "</li>";
echo "<li><strong>/.dockerenv existe:</strong> " . (file_exists('/.dockerenv') ? 'SIM' : 'NÃO') . "</li>";
echo "<li><strong>Hostname:</strong> " . gethostname() . "</li>";
echo "</ul>";

echo "<h3>Testando Conexões:</h3>";

foreach ($hosts as $host) {
    echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
    echo "<strong>Tentando conectar em: $host</strong><br>";
    
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
            $db_user,
            $db_pass,
            [PDO::ATTR_TIMEOUT => 3]
        );
        echo "<span style='color: green;'>✓ CONECTADO COM SUCESSO!</span><br>";
        
        // Testa query
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<span style='color: blue;'>→ Banco possui {$result['total']} usuários</span>";
        
        $pdo = null;
        break; // Para no primeiro sucesso
        
    } catch (PDOException $e) {
        echo "<span style='color: red;'>✗ FALHOU: " . $e->getMessage() . "</span>";
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<h3>Comandos para seu amigo executar no WSL:</h3>";
echo "<pre style='background: #f4f4f4; padding: 15px;'>";
echo "# Ver status dos containers\n";
echo "docker-compose ps\n\n";
echo "# Ver logs do banco de dados\n";
echo "docker-compose logs db\n\n";
echo "# Ver logs do web server\n";
echo "docker-compose logs web\n\n";
echo "# Reiniciar tudo\n";
echo "docker-compose down\n";
echo "docker-compose up -d\n\n";
echo "# Verificar conectividade de dentro do container web\n";
echo "docker exec -it siteacademia_web ping -c 3 db\n";
echo "docker exec -it siteacademia_web ping -c 3 siteacademia_db\n";
echo "</pre>";
?>
