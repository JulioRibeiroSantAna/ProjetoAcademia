#!/bin/bash
set -e

echo "======================================"
echo "Forçando recriação do banco de dados"
echo "======================================"

# Aguarda MySQL estar pronto
until mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
    echo "Aguardando MySQL iniciar..."
    sleep 2
done

echo "MySQL pronto! Verificando banco..."

# Verifica se o banco existe
DB_EXISTS=$(mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='sistema_nutricao';" | grep -c sistema_nutricao || true)

if [ "$DB_EXISTS" -gt 0 ]; then
    # Verifica se há tabelas
    TABLE_COUNT=$(mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sistema_nutricao -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='sistema_nutricao';" -s -N)
    
    echo "Banco existe com $TABLE_COUNT tabelas"
    
    if [ "$TABLE_COUNT" -eq 0 ]; then
        echo "⚠️  Banco existe mas SEM tabelas! Forçando recriação..."
        mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "DROP DATABASE sistema_nutricao;"
        mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE sistema_nutricao CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        echo "✅ Banco recriado! SQL será executado..."
    else
        echo "✅ Banco já tem tabelas, mantendo dados existentes"
    fi
else
    echo "Banco não existe, será criado automaticamente"
fi

echo "======================================"
