<?php
/**
 * Script de teste de conexão com banco de dados
 * Acesse: http://localhost:8080/test_connection.php
 */

echo "<h2>Teste de Conexão com Banco de Dados</h2>";

echo "<div style='background: #d1ecf1; padding: 15px; border: 1px solid #bee5eb; border-radius: 5px; margin: 10px 0;'>";
echo "<strong>ℹ️ IMPORTANTE:</strong> O código PHP roda <strong>DENTRO</strong> do container 'web', ";
echo "então ele sempre se conecta ao banco usando o hostname '<strong>db</strong>' (nome do serviço no docker-compose).";
echo "</div>";

// Testa usando as configurações do sistema
require_once 'config.php';

echo "<h3>Configuração Atual do Sistema:</h3>";
echo "<ul>";
echo "<li><strong>DB_HOST:</strong> " . DB_HOST . "</li>";
echo "<li><strong>DB_NAME:</strong> " . DB_NAME . "</li>";
echo "<li><strong>DB_USER:</strong> " . DB_USER . "</li>";
echo "<li><strong>Hostname do container:</strong> " . gethostname() . "</li>";
echo "<li><strong>/.dockerenv existe:</strong> " . (file_exists('/.dockerenv') ? '✅ SIM (rodando no Docker)' : '❌ NÃO') . "</li>";
echo "</ul>";

echo "<h3>Teste de Conexão:</h3>";
echo "<div style='margin: 10px 0; padding: 15px; border: 2px solid #ccc; border-radius: 5px;'>";

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_TIMEOUT => 5]
    );
    
    echo "<h2 style='color: green;'>✅ CONECTADO COM SUCESSO!</h2>";
    
    // Testa queries
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $usuarios = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM profissionais");
    $profissionais = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM agendamentos");
    $agendamentos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Estatísticas do Banco:</h3>";
    echo "<ul>";
    echo "<li>👥 Usuários cadastrados: <strong>{$usuarios['total']}</strong></li>";
    echo "<li>👨‍⚕️ Profissionais cadastrados: <strong>{$profissionais['total']}</strong></li>";
    echo "<li>📅 Agendamentos registrados: <strong>{$agendamentos['total']}</strong></li>";
    echo "</ul>";
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin-top: 15px;'>";
    echo "<strong>✅ Sistema pronto para uso!</strong><br>";
    echo "Acesse: <a href='index.php'>http://localhost:8080</a>";
    echo "</div>";
    
    $pdo = null;
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ FALHA NA CONEXÃO</h2>";
    echo "<p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin-top: 15px;'>";
    echo "<h3>🔧 Soluções:</h3>";
    echo "<ol>";
    echo "<li>Verifique se o container do banco está rodando:<br><code>docker-compose ps</code></li>";
    echo "<li>Aguarde 30-60 segundos para o MySQL inicializar completamente</li>";
    echo "<li>Veja os logs do banco:<br><code>docker-compose logs db</code></li>";
    echo "<li>Reinicie os containers:<br><code>docker-compose down && docker-compose up -d</code></li>";
    echo "</ol>";
    echo "</div>";
}

echo "</div>";

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
