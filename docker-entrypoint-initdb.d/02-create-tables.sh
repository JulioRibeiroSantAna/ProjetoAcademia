#!/bin/bash
set -e

echo "========================================="
echo "CRIANDO ESTRUTURA DE TABELAS"
echo "========================================="

# Verificar se as tabelas já existem
TABLE_COUNT=$(mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" -sN -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '${MYSQL_DATABASE}';")

if [ "$TABLE_COUNT" -gt 0 ]; then
    echo "⚠️  Tabelas já existem. Pulando criação..."
    exit 0
fi

echo "Criando tabelas..."

# Importar SQL com as tabelas
if [ -f /docker-entrypoint-initdb.d/sistema_nutricao.sql ]; then
    mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" < /docker-entrypoint-initdb.d/sistema_nutricao.sql
    echo "✅ Tabelas criadas com sucesso!"
else
    echo "❌ Arquivo sistema_nutricao.sql não encontrado!"
    exit 1
fi

# Verificar criação
TABLE_COUNT=$(mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" -sN -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '${MYSQL_DATABASE}';")
echo "✅ Total de tabelas criadas: $TABLE_COUNT"

echo "========================================="
