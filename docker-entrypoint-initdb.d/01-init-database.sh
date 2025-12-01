#!/bin/bash
set -e

echo "========================================="
echo "INICIALIZANDO BANCO DE DADOS"
echo "========================================="

# Aguardar MySQL estar pronto
echo "Aguardando MySQL inicializar..."
until mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
    echo "MySQL ainda não está pronto..."
    sleep 2
done

echo "✅ MySQL está pronto!"

# Criar banco se não existir
echo "Criando banco de dados '${MYSQL_DATABASE}'..."
mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    
    -- Criar usuário se não existir
    CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';
    
    -- Conceder privilégios
    GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '${MYSQL_USER}'@'%';
    FLUSH PRIVILEGES;
    
    SELECT 'Banco de dados e usuário configurados com sucesso!' as Status;
EOSQL

echo "✅ Configuração do banco concluída!"
echo "========================================="
