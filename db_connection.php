<?php
/**
 * ARQUIVO DE CONEXÃO COM O BANCO DE DADOS
 * 
 * Este arquivo estabelece a conexão com o banco de dados MySQL.
 * Toda vez que uma página precisa buscar ou salvar informações no banco,
 * ela inclui este arquivo para ter acesso à variável $pdo.
 * 
 * PDO (PHP Data Objects) é uma forma moderna e segura de trabalhar com bancos de dados.
 */

// Inclui o arquivo de configuração para ter acesso às constantes do banco
require_once __DIR__ . '/config.php';

// Tento fazer a conexão com o banco de dados
try {
    // Crio um novo objeto PDO que representa a conexão
    // Os parâmetros são: tipo do banco:host;nome do banco;charset
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,      // Usuário do banco
        DB_PASS       // Senha do banco
    );
    
    // Configuro o PDO para mostrar erros detalhados (útil para desenvolvimento)
    // Se der algum erro SQL, ele vai aparecer na tela
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Desabilita a emulação de prepared statements para maior segurança
    // Isso faz o MySQL preparar as consultas de verdade, evitando SQL Injection
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
} catch (PDOException $e) {
    // Se der erro na conexão, mostro a mensagem e paro o script
    // Em produção, seria melhor mostrar uma mensagem genérica
    die('Erro na conexão com o banco de dados: ' . $e->getMessage());
}

// Agora a variável $pdo está disponível para fazer consultas SQL
// Exemplo de uso: $stmt = $pdo->query("SELECT * FROM usuarios");
?>