<?php
// Configurações do banco de dados
$host = 'db'; // Nome do serviço definido no docker-compose
$dbname = 'sistema_nutricao';
$username = 'user';
$password = 'password';

try {
    // Conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Função para evitar SQL Injection
function safeQuery($pdo, $query, $params = []) {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt;
}

// Função para testar a conexão e listar usuários
function testConnection($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Conexão bem-sucedida! Usuários encontrados: <br>";
        foreach ($usuarios as $usuario) {
            echo "ID: {$usuario['id_usuario']}, Nome: {$usuario['nome']}, Email: {$usuario['email']}<br>";
        }
    } catch (PDOException $e) {
        echo "Erro ao testar a conexão: " . $e->getMessage();
    }
}

// Testando a conexão
testConnection($pdo);
?>